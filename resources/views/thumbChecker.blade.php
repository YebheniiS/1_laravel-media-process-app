@foreach($thumbs as $thumb)
    <p>Project Id {{ $thumb->project_id }}</p>
    <p>{!! $thumb->url !!}</p>
    <img src="{{ $thumb->thumbnail_url }}" style="margin-top: 20px;max-width: 250px;">
    @if($thumb->url)
    @endif
    <hr>
@endforeach