<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <title>Document</title>
</head>
<body>
<div class="tiles" style="width:{{$boardgameWidth}}px">  
  @for ($x = $minX; $x <= $maxX; $x++)
      @for ($y = $minY; $y <= $maxY; $y++)
          <?php $tile = $tiles->first(); ?>
          @if ($tile != null && $x == $tile->position_x && $y == $tile->position_y)
              <div class="tile tile-{{$tile->color}}">
                {{ $tile->shape }}
              </div>
              <?php $tiles->shift() ?>
          @else
              <div class="tile empty"></div>
          @endif
      @endfor
  @endfor
</div>
  
</body>
</html>