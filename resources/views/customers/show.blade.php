@extends('layouts.app')
@section('content')

    <show-customer></show-customer>

@stop

@section('script')
<script type="text/javascript">
    const id_customer =  {!! $id !!} 
</script>
@stop