<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Temperatura</th>
        <th scope="col">Fecha y hora</th>
        
      </tr>
    </thead>
    <tbody>
      @php($i=0)
      @foreach ($tems as $tem)
      @php($i++)
        <tr>
          <th scope="row">{{ $i }}</th>
          <td>{{ $tem->valor }}</td>
          <td>{{ $tem->created_at }} <small></small></td>
        </tr>
      @endforeach
      

    </tbody>
  </table>