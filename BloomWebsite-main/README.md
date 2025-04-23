```mermaid
erDiagram

    sanpham {
        int id PK
        varchar name
        float price
        float discount
        int quantity
        varchar image_url
        int type_id FK
        text description
        datetime created_at
        datetime updated_at
    }

    loaisanpham {
        int id PK
        varchar name_type
        int parent_id FK
        datetime created_at
        datetime updated_at
    }

    sanpham_lienquan {
        int id PK
        int sanpham_id FK
        int sanpham_lienquan_id FK
        datetime created_at
    }

    nguoidung {
        int id PK
        varchar email
        varchar name
        varchar password
        varchar role
        varchar phone
        text address
        datetime created_at
        datetime updated_at
        datetime last_login
    }

    giohang {
        int id PK
        int user_id FK
        datetime created_at
        datetime updated_at
    }

    giohang_chitiet {
        int id PK
        int cart_id FK
        int product_id FK
        int quantity
        datetime created_at
        datetime updated_at
    }

    donhang {
        int id PK
        int user_id FK
        float total_price
        float shipping_fee
        varchar status
        varchar payment_method
        varchar payment_status
        text shipping_address
        varchar shipping_name
        varchar shipping_phone
        text note
        datetime created_at
        datetime updated_at
    }

    donhang_chitiet {
        int id PK
        int order_id FK
        int product_id FK
        varchar product_name
        int quantity
        float price
        float discount
        datetime created_at
    }

    danhgia {
        int id PK
        int user_id FK
        int product_id FK
        int order_id FK
        int rating
        text comment
        varchar images
        datetime created_at
        datetime updated_at
    }

    yeuthich {
        int id PK
        int user_id FK
        int product_id FK
        datetime created_at
    }

    sanpham ||--o{ loaisanpham : "type_id"
    loaisanpham ||--o{ loaisanpham : "parent_id"
    sanpham_lienquan }o--|| sanpham : "sanpham_id"
    sanpham_lienquan }o--|| sanpham : "sanpham_lienquan_id"
    giohang }o--|| nguoidung : "user_id"
    giohang_chitiet }o--|| giohang : "cart_id"
    giohang_chitiet }o--|| sanpham : "product_id"
    donhang }o--|| nguoidung : "user_id"
    donhang_chitiet }o--|| donhang : "order_id"
    donhang_chitiet }o--|| sanpham : "product_id"
    danhgia }o--|| nguoidung : "user_id"
    danhgia }o--|| sanpham : "product_id"
    danhgia }o--|| donhang : "order_id"
    yeuthich }o--|| nguoidung : "user_id"
    yeuthich }o--|| sanpham : "product_id"
