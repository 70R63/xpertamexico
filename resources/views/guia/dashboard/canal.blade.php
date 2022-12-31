@if ($objeto->canal=='RET')
    <td>{{ $objeto->contacto_d }}</td>
    <td>{{ $objeto->contacto }}</td>
@else
    <td>{{ $objeto->contacto }}</td>
    <td>{{ $objeto->contacto_d }}</td>
@endif


