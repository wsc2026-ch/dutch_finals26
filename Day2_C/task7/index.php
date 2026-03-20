<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product list</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 40px auto;
        }

        h2 {
            margin-bottom: 16px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .product-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .product-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .product-price {
            color: #444;
            font-size: 14px;
        }
    </style>
</head>
<body>

<h2>Products (sorted by price)</h2>

<div class="products">
    <?php
    $products = [
        [
            "name" => "Banana",
            "price" => 1.20
        ],
        [
            "name" => "Apple",
            "price" => 1.50
        ],
        [
            "name" => "Mango",
            "price" => 3.25
        ],
        [
            "name" => "Orange",
            "price" => 1.10
        ],
        [
            "name" => "Kiwi",
            "price" => 2.00
        ]
    ];

    // TODO:
    // 1. Sort $products based on  "price" (lowest at the top
    // 2. Print pronducts as followed
    //    <div class="product-card">
    //      <div class="product-name">Apple</div>
    //      <div class="product-price">€1,50</div>
    //    </div>
    function sort_by_price($a, $b) {
        return $a["price"] > $b["price"];
    }

    usort($products, 'sort_by_price');
    foreach ($products as $i => $product) {
        echo '<div class="product-card"><div class="product-name">' . $product["name"] . '</div><div class="product-price">€' . $product["price"] . '</div></div>';
    }
    ?>
</div>

</body>
</html>
