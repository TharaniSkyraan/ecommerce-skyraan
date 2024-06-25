<div>
    <section class="about-us">
        <section class="about-us-banner">
            <div class="py-3 container-fluid">
                <div class="row">
                    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%23000000'/%3E%3C/svg%3E&#34;);z-index: 1;" aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ route('ecommerce.home') }}" class="">Home</a></li>
                            <li class="breadcrumb-item  active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                    <div style="z-index: 1; ">
                        <h1 class="buy-color text-center">About Us</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="container py-xl-5 py-lg-5 py-md-4 py-sm-4 py-3">
            @foreach ($page_cnt as $content)
                <div class="content-item">
                    {!! $content->content !!}
                </div>
            @endforeach
        </section>
    </section>
</div>
