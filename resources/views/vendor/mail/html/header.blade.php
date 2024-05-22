@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    @if (trim($slot) === 'Laravel')
        <img src="https://californila.com/cdn/shop/files/white-logo_150x_de4da7f7-f675-41e9-b53f-1d3204c539e6_150x.webp?v=1657260618" class="logo w-full" alt="Laravel Logo" style="width:100%;height:100%;">
    @else
        <img src="https://californila.com/cdn/shop/files/white-logo_150x_de4da7f7-f675-41e9-b53f-1d3204c539e6_150x.webp?v=1657260618" class="logo w-full" alt="Laravel Logo" style="width:100%;height:100%;">
    @endif
</a>
</td>
</tr>
