@extends('layouts.app')
@section('content')

    <show-product></show-product>

@stop

@section('script')
<script type="text/javascript">
    const id_product =  {!! $id !!} 
</script>
@stop