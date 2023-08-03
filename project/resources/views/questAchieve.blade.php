@extends('layout.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/quest.css') }}">  
@endsection

@section('title', '퀘스트 업적')

@section('contents')
<div class="con">
    <h1>퀘스트 업적</h1>
    <div class="questAchieveCon">
        <div>
            <h4>대표 칭호</h4>
            <div>
                @if (isset($rep->quest_style))
                    <strong>{{$rep->quest_style}}</strong>
                @else
                    <span>대표 칭호가 없습니다.</span>
                @endif
            </div>
        </div>
        <section class="articles">
            @forelse ($data as $item)
            <a
            @if ($item->rep_flg !== '1')
                href="{{route('quest.repFlgUpdate', ['id' => $item->quest_status_id])}}"
            @endif 
            >
                <article
                @if ($item->rep_flg === '1')
                    id="colorCard"
                @endif
                >
                    <div class="article-wrapper">
                        <figure>
                            <img src="{{asset('img/quest_'.$item->quest_cate_id.'.jpg')}}" alt="" />
                        </figure>
                        <div class="article-body">
                            <h2>{{$item->quest_name}}</h2>
                            <p>
                                {{$item->quest_content}}
                            </p>
                            <p>
                                달성일 {{mb_substr($item->updated_at, 0, 10)}}
                            </p>
                        </div>
                    </div>
                </article>
            </a>
            @empty
                <div>완료된 퀘스트가 없습니다.</div>
            @endforelse
        </section>
    </div>
</div>

@endsection

@section('js')
    <script src="{{asset('js/quest.js')}}"></script>
@endsection