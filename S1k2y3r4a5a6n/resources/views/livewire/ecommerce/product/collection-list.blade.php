@php
$baseDuration = 13; $duration = (count($collections) / 5) * $baseDuration;
@endphp
<style>

.marquee__group1
{
    animation: scroll1 {{$duration}}s linear infinite;
}
</style>
<section class="marquee_content2">
    <div class="marquee py-4" >
        <div class="marquee__group1">
            @foreach($collections as $collection)
                @if(empty($collection['product_slug']))
                    <a href="{{ route('ecommerce.product.list', ['type' => 'collection', 'slug' => $collection['slug']]) }}">
                        <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="{{$collection['name']}}">
                    </a>
                @else
                    <a href="{{ route('ecommerce.product.detail', ['slug' => $collection['product_slug']]) }}?prdRef={{ \Carbon\Carbon::parse($collection['product_created'])->timestamp }}">
                        <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="{{$collection['name']}}">
                    </a>
                @endif
            @endforeach
        </div>
        <div aria-hidden="true" class="marquee__group1">
            @foreach($collections as $collection)
                @if(empty($collection['product_slug']))
                    <a href="{{ route('ecommerce.product.list', ['type' => 'collection', 'slug' => $collection['slug']]) }}">
                        <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="{{$collection['name']}}">
                    </a>
                @else
                    <a href="{{ route('ecommerce.product.detail', ['slug' => $collection['product_slug']]) }}?prdRef={{ \Carbon\Carbon::parse($collection['product_created'])->timestamp }}">
                        <img src="{{ asset('storage') }}/{{$collection['image']}}" alt="{{$collection['name']}}">
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>