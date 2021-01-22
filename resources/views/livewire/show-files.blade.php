<div>
  <table>
    <thead>
      <th>File</th>
    </thead>
    <tbody>
      @foreach ($files as $file)
        <tr>
          <td>{{$file->filename}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
