<h1>Ticket</h1>
<p>{{ $content }}</p>
<p><b>Gönderen: </b>{{ $sender }}</p>
<p>
	<b>Detaylar:</b>
	<br>
	@php echo $details; @endphp
</p>
<a class="btn btn-primary btn-md" href="{{ $link }}">Bileti gör</a>
