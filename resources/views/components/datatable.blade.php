<div class="">
    <table id="{{ $id }}" class="table" style="width:100%">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>
