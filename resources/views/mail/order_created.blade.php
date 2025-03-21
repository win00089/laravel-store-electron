<p>Уважаемый {{ $name }}</p>

Ваш заказ на сумму {{$fullSum}} создан

<table>
  <tbody>
    @foreach ($order->products as $product)
    <tr>
      <td>
        <a href="{{route('product', [$product->category->code, $product->code])}}">
          <img height="56px" src="{{ Storage::url($product->image) }}" alt="">
          {{$product->__('name')}}
        </a>
      </td>
      <td>
        <span class="badge">{{$product->pivot->count}}</span>
        <div class="btn-group form-inline">
          {!! $product->__('description') !!}
        </div>
      </td>
      <td>{{ $product->price }}</td>
      <td>{{ $product->getPriceForCount() }}</td>
    </tr>
    @endforeach
  </tbody>
</table>