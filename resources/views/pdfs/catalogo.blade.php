<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Catálogo Asteca</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        .produtos-grid {
            display: block;
            width: 100%;
            margin: 0 auto;
        }

        .produtos-row {
            width: 100%;
            clear: both;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .produto {
            float: left;
            width: 30%;
            margin: 0 1.5%;
            padding: 10px;
            text-align: center;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .produto img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }

        .info {
            text-align: center;
        }

        .marca {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .nome {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .quantidade {
            color: #444;
            font-size: 12px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('astecahinomoto_logo.png') }}" alt="Logo Asteca" style="max-width: 200px;">
        <h1>Catálogo de Produtos</h1>

        @if($filtros['marca'] || $filtros['produto'] || $filtros['linha'])
        <div class="filtros">
            Filtros aplicados:
            @if($filtros['marca']) <strong>Marca:</strong> {{ $filtros['marca'] }} @endif
            @if($filtros['produto']) <strong>Produto:</strong> {{ $filtros['produto'] }} @endif
            @if($filtros['linha']) <strong>Linha:</strong> {{ $filtros['linha'] }} @endif
        </div>
        @endif
    </div>

    <div class="produtos-grid">
        @foreach($skus->chunk(3) as $rowSkus)
        <div class="produtos-row clearfix">
            @foreach($rowSkus as $sku)
            <div class="produto">
                @if($sku->imagem)
                <img src="{{ public_path('storage/skus/thumbnails/' . $sku->imagem) }}"
                    alt="{{ $sku->produto->nome }}"
                    onerror="this.src='{{ public_path('assets/sem_imagem.png') }}'">
                @else
                <img src="{{ public_path('images/sem-imagem.png') }}"
                    alt="Imagem indisponível">
                @endif

                <div class="info">
                    <div class="marca">
                        @php
                        $marca = $sku->produto->getMarcaAttribute();
                        @endphp
                        {{ $marca ? $marca->nome : 'Sem marca' }}
                    </div>
                    <div class="nome">{{ $sku->produto->nome }}</div>
                    <div class="quantidade">{{ $sku->quantidade }}</div>
                </div>
            </div>
            @endforeach
        </div>

        @if(!$loop->last)
        <div class="page-break"></div>
        @endif
        @endforeach
    </div>
</body>

</html>