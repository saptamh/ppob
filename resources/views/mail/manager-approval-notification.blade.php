<style>
.button {/* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 0 10px;
}

.button2 {background-color: #008CBA;} /* Blue */
.button3 {background-color: #f44336;} /* Red */
</style>
Hi,

<p>Berikut ada informasi payment yang di <b>{{ $content['status'] }} </b></p>
<p>Dengan rincian sebagai berikut: </p>
<div style="margin-left: 10px;">
    <table>
    @foreach($content as $key=>$v)
        <tr><td>{{ ucwords(str_replace("_", " ", $key)) }}</td><td>: {{ $v }}</td></tr>
    @endforeach
    </table>
</div>
