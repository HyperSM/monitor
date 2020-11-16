<h1>test</h1>

@foreach ($responseArray as $item)
  <h3>{{$item->Attributes->Attribute[0]->AttrValue}}</h3>
@endforeach
