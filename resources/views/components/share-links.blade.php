<p class="font-bold mb-4">Share Post</p>
<a target="_blank" href="https://web.facebook.com/sharer/sharer.php?u={{ $attributes['url'] }}"><span
        class="fa-brands fa-facebook p-3 border-2 rounded hover:bg-gray-400 mr-2"></span></a>
<a target="_blank" href="https://twitter.com/share?text={{ $attributes['title']}}&url={{ $attributes['url'] }}"><span
        class="fa-brands fa-twitter p-3 border-2 rounded hover:bg-gray-400 mr-2"></span></a>
<a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $attributes['url'] }}"><span
        class="fa-brands fa-linkedin p-3 border-2 rounded hover:bg-gray-400 mr-2"></span></a>
<a target="_blank"
    href="https://api.whatsapp.com/send?text={{ $attributes['title'] .'\n\n'}}{{ $attributes['url'] }}"><span
        class="fa-brands fa-whatsapp p-3 border-2 rounded hover:bg-gray-400 mr-2"></span></a>
