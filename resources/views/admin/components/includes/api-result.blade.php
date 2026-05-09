<h6 class="card-title">Result of API</h6>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th width="15%">Endpoint</th>
                <td width="85%">{{ $endpoint }}</td>
            </tr>
            <tr>
                <th>Parameters</th>
                <td>
                    <ul>
                        @foreach ($parameters as $key => $value)
                            <li>
                                <b>{{ $key }}</b>
                                :

                                @if (is_array($value))
                                    <pre>{{ print_r($value, true) }}</pre>
                                @else
                                    {{ $value }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Response</th>
                <td>
                    @if (is_string($response))
                        <pre style="white-space: pre-wrap">
{{ json_encode(json_decode($response), JSON_PRETTY_PRINT) }}</pre
                        >
                    @elseif (is_array($response))
                        <pre><code>{{ json_encode($response, JSON_PRETTY_PRINT) }}</code></pre>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
