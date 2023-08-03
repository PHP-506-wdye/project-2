<?php

/*****************************************************
 * 프로젝트명   : project-2
 * 디렉토리     : Controllers
 * 파일명       : QuestController.php
 * 이력         : v001 0526 AR.Choe new
 *****************************************************/

namespace App\Http\Controllers;

use App\Models\Alarm;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\QuestCate;
use App\Models\QuestLog;
use App\Models\QuestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestController extends Controller
{
    public function index()
    {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 퀘스트 정보 획득
        $result = QuestCate::get();

        // 이미 수락된 퀘스트가 있는지 확인
        $questLog = QuestStatus::where('user_id', Auth::user()->user_id)
            ->where('complete_flg', '0')
            ->first();

        if(!isset($questLog)) {
            return view('questList')->with('data', $result)->with('flg', 1);
        }

        return view('questList')->with('data', $result);
    }

    public function store(Request $req) {
        // 로그인 확인
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        DB::transaction(function () use ($req) {
            // 퀘스트 로그 정보 획득
            $quest = QuestCate::find($req->id);
            $quest_period = $quest->min_period;

            // 퀘스트 스탯 테이블 인서트
            $quest_status_id = QuestStatus::insertGetId([
                'user_id'           => Auth::user()->user_id,
                'quest_cate_id'     => $req->id,
                'alarm_time'        => $req->time,
                'created_at'        => Carbon::now()
            ]);
            
            // 퀘스트 로그 테이블 인서트
            for ($i=0; $i < $quest_period; $i++) { 
                $questLog = new QuestLog([
                    'quest_status_id'   => $quest_status_id,
                    'effective_date'    => Carbon::now()->addDays($i)->format("Y-m-d"),
                ]);
                $questLog->save();
            }
        });
        
        // 퀘스트 관리 페이지 리턴
        return redirect()->route('quest.show');
    }

    public function show() {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        $flg = 0;
        
        // 진행중 혹은 성공한 퀘스트 정보 획득
        $questStatus = QuestStatus::where('user_id', Auth::user()->user_id)
            ->first();
        
        // 진행중 혹은 성공한 퀘스트가 없을 때
        if (!isset($questStatus)) {
            // 플래그 변경
            $flg = 1;
            return view('questDetail')->with('flg', $flg);
        }

        // 성공했을 때
        if ($questStatus->complete_flg === 1) {
            // 플래그 변경
            $flg = 3;
            return view('questDetail')->with('id', $questStatus->quest_status_id)->with('flg', $flg);
        }

        // 전체 로그 정보 획득
        $questLog = DB::table('quest_logs')
            ->where('quest_status_id', $questStatus->quest_status_id)
            ->get();

        // 첫날 제외 어제 수행 여부 확인, 안되어있으면 실패
        if ($questLog[0]->effective_date !== Carbon::now()->format("Y-m-d")) {
            $yesterdayLog = DB::table('quest_logs')
                ->where('quest_status_id', $questStatus->quest_status_id)
                ->where('effective_date', Carbon::now()->subDays(1)->format("Y-m-d"))
                ->first();

            if ($yesterdayLog->complete_flg === '0') {
                // 플래그 변경
                $flg = 2;

                // 퀘스트 실패 처리
                QuestStatus::destroy($yesterdayLog->quest_status_id);
            }
        }

        // 진행중인 퀘스트 정보 획득
        $questInfo = QuestCate::find($questStatus->quest_cate_id);

        // 진행도 계산
        $period = $questInfo->min_period;
        $nowComplete = 0;
        foreach ($questLog as $val) {
            if ($val->complete_flg === '1') {
                ++$nowComplete;
            }
        }
        $ratio = round($nowComplete / $period * 100, 1);

        $arrRatio = [
            'period'    => $period,
            'complete'  => $nowComplete,
            'ratio'     => $ratio,
        ];

        return view('questDetail')
            ->with('info', $questInfo)
            ->with('logs', $questLog)
            ->with('ratio', $arrRatio)
            ->with('questStat', $questStatus)
            ->with('flg', $flg);
    }

    public function update(Request $req, $id) {
        DB::table('quest_logs')
            ->where('quest_log_id', $id)
            ->update([
                'complete_flg' => '1',
                'updated_at' => Carbon::now(),
            ]);

        $logs = DB::table('quest_logs')
            ->where('quest_status_id', $req->statId)
            ->latest('quest_log_id')
            ->first();
        
        $lastLogDate = $logs->effective_date;

        // 오늘이 마지막 날인지 확인
        if ($lastLogDate === Carbon::now()->format("Y-m-d")) {
            $questStat = QuestStatus::find($req->statId);
            // 퀘스트 성공 처리
            $questStat->complete_flg = '1';
            $questStat->save();
        }

        return redirect()->route('quest.show');
    }
    

    public function destroy($id) {
        
        DB::table('quest_statuses')
            ->where('quest_status_id', $id)
            ->update(['deleted_at' => Carbon::now()]);

        return redirect()->route('quest.show');
    }

    public function questAlarmInsert() {
        // 알림 받기를 설정한 진행중인 퀘스트 목록 획득
        $questStat = QuestStatus::join('user_infos', 'user_infos.user_id', 'quest_statuses.user_id')
            ->where('quest_statuses.complete_flg', '0')
            ->where('user_infos.user_status', '1')
            ->whereNotNull('quest_statuses.alarm_time')
            ->get();

        // 정해진 시간에 알림 인서트
        foreach ($questStat as $item) {
            if ($item->alarm_time == Carbon::now()->format("H")) {
                $log = DB::table('quest_logs')
                    ->where('quest_status_id', $item->quest_status_id)
                    ->where('effective_date', Carbon::now()->format("Y-m-d"))
                    ->first();

                if ($log->complete_flg === '0') {
                    $alarm= new Alarm;
                    $alarm->user_id = $item->user_id;
                    $alarm->alarm_type = '0';  // 퀘스트 알림 타입
                    $alarm->save();
                }
            }
        }
    }

    public function questAchieve() {
        if(auth()->guest()) {
            return redirect()->route('user.login');
        }

        // 유저의 가장 처음 성공한 퀘스트 목록 획득
        $list = DB::table('quest_statuses')
        ->join('quest_cates', 'quest_cates.quest_cate_id', 'quest_statuses.quest_cate_id')
        ->where('quest_statuses.complete_flg', '1')
        ->where('quest_statuses.user_id', Auth::user()->user_id)
        ->orderBy('created_at')
        ->get()
        ->unique('quest_cate_id');

        // 현재 대표 칭호 획득
        $style = DB::table('quest_statuses')
            ->join('quest_cates', 'quest_cates.quest_cate_id', 'quest_statuses.quest_cate_id')
            ->where('quest_statuses.user_id', Auth::user()->user_id)
            ->where('quest_statuses.rep_flg', '1')
            ->first();

        if(!empty($style)) {
            return view('questAchieve')->with('data', $list)->with('rep', $style);
        }

        return view('questAchieve')->with('data', $list);
    }

    public function repFlgUpdate($id) {
        // 해당 퀘스트 정보 획득
        $questStatus = DB::table('quest_statuses')
            ->where('quest_status_id', $id)
            ->first();
        
        // 해당 퀘스트의 유저가 아니면 리다이렉트
        if (Auth::user()->user_id !== $questStatus->user_id) {
            return redirect()->back();
        }

        // 해당 유저의 성공한 퀘스트 rep_flg 초기화
        DB::table('quest_statuses')
            ->where('user_id', $questStatus->user_id)
            ->where('complete_flg', '1')
            ->where('rep_flg','1')
            ->update([
                'rep_flg' => '0'
            ]);

        // 해당 퀘스트와 rep_flg 1로 변경
        DB::table('quest_statuses')
            ->where('quest_status_id', $id)
            ->update([
                'rep_flg' => '1'
            ]);

        return redirect()->route('quest.questAchieve');
    }

    public function alarmUpdate(Request $req, $id) {
        QuestStatus::find($id)->update(['alarm_time' => $req->time]);
        return redirect()->route('quest.show');
    }
}