<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ url('/') }}</loc>
    </url>

    @foreach($pages as $p)
        @foreach($p->translations as $t)
            <url>
                <loc>{{ route('page.show', ['locale'=>$t->locale,'slug'=>$t->slug]) }}</loc>
            </url>
        @endforeach
    @endforeach

    @foreach($news as $n)
        @foreach($n->translations as $t)
            <url>
                <loc>{{ route('news.show', ['locale'=>$t->locale,'slug'=>$t->slug]) }}</loc>
            </url>
        @endforeach
    @endforeach

</urlset>