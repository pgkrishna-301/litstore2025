# OrderDetailController API Examples

## 📋 **Restructured OrderDetailController**

The OrderDetailController has been completely restructured with the following fields:
- `order_id` (string)
- `user_id` (string)
- `products` (array of objects with: `product_id`, `reflector_color`, `location` (array), `qty`, `price`)
- `total_price` (decimal)
- `status` (integer)
- `cash` (string)
- `credit` (string)
- `received` (string)
- `pending` (string)
- `customer_id` (foreign key to architects table)
- `delivery_date` (date)

---

## 🚀 **API Endpoints & JSON Examples**

### 1. **POST** - Create New Order
**URL:** `POST /api/order-details`

```json
{
    "order_id": "ORD-2025-001",
    "user_id": "USER123",
    "products": [
        {
            "product_id": 1,
            "reflector_color": "White",
            "location": ["Mumbai", "Delhi"],
            "qty": 3,
            "price": 2500.00
        },
        {
            "product_id": 2,
            "reflector_color": "Silver",
            "location": ["Chennai", "Hyderabad"],
            "qty": 2,
            "price": 1800.00
        }
    ],
    "total_price": 11100.00,
    "status": 1,
    "cash": "5000.00",
    "credit": "6100.00",
    "received": "5000.00",
    "pending": "6100.00",
    "customer_id": 1,
    "delivery_date": "2025-08-15"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Order stored successfully!",
    "data": {
        "id": 1,
        "order_id": "ORD-2025-001",
        "user_id": "USER123",
        "products": [
            {
                "product_id": 1,
                "reflector_color": "White",
                "location": ["Mumbai", "Delhi"],
                "qty": 3,
                "price": 2500.00
            },
            {
                "product_id": 2,
                "reflector_color": "Silver",
                "location": ["Chennai", "Hyderabad"],
                "qty": 2,
                "price": 1800.00
            }
        ],
        "total_price": "11100.00",
        "status": 1,
        "cash": "5000.00",
        "credit": "6100.00",
        "received": "5000.00",
        "pending": "6100.00",
        "customer_id": 1,
        "delivery_date": "2025-08-15",
        "created_at": "2025-07-31T10:30:00.000000Z",
        "updated_at": "2025-07-31T10:30:00.000000Z",
        "customer": {
            "id": 1,
            "name": "John Architect",
            "firm_name": "Design Studio",
            "email": "john@designstudio.com",
            "ph_no": "9876543210",
            "shipping_address": "123 Main St, Mumbai",
            "status": 1,
            "select_architect": 1
        }
    }
}
```

---

### 2. **GET** - Get Order by Order ID
**URL:** `GET /api/order-details/order/{order_id}`

**Example:** `GET /api/order-details/order/ORD-2025-001`

**Expected Response:**
```json
{
    "success": true,
    "message": "Order retrieved successfully!",
    "data": {
        "id": 1,
        "order_id": "ORD-2025-001",
        "user_id": "USER123",
        "products": [
            {
                "product_id": 1,
                "reflector_color": "White",
                "location": ["Mumbai", "Delhi"],
                "qty": 3,
                "price": 2500.00
            },
            {
                "product_id": 2,
                "reflector_color": "Silver",
                "location": ["Chennai", "Hyderabad"],
                "qty": 2,
                "price": 1800.00
            }
        ],
        "total_price": "11100.00",
        "status": 1,
        "cash": "5000.00",
        "credit": "6100.00",
        "received": "5000.00",
        "pending": "6100.00",
        "customer_id": 1,
        "delivery_date": "2025-08-15",
        "created_at": "2025-07-31T10:30:00.000000Z",
        "updated_at": "2025-07-31T10:30:00.000000Z",
        "customer": {
            "id": 1,
            "name": "John Architect",
            "firm_name": "Design Studio",
            "email": "john@designstudio.com",
            "ph_no": "9876543210",
            "shipping_address": "123 Main St, Mumbai",
            "status": 1,
            "select_architect": 1
        }
    }
}
```

---

### 3. **GET** - Get All Orders
**URL:** `GET /api/order-details`

**Expected Response:**
```json
{
    "success": true,
    "message": "All orders retrieved successfully.",
    "data": [
        {
            "id": 1,
            "order_id": "ORD-2025-001",
            "user_id": "USER123",
            "products": [...],
            "total_price": "11100.00",
            "status": 1,
            "cash": "5000.00",
            "credit": "6100.00",
            "received": "5000.00",
            "pending": "6100.00",
            "customer_id": 1,
            "delivery_date": "2025-08-15",
            "created_at": "2025-07-31T10:30:00.000000Z",
            "updated_at": "2025-07-31T10:30:00.000000Z",
            "customer": {...}
        }
    ]
}
```

---

### 4. **PUT** - Update Order by Customer ID
**URL:** `PUT /api/order-details/customer/{customer_id}`

**Example:** `PUT /api/order-details/customer/1`

```json
{
    "status": 2,
    "products": [
        {
            "product_id": 1,
            "reflector_color": "White",
            "location": ["Mumbai", "Delhi", "Pune"],
            "qty": 5,
            "price": 2500.00
        }
    ],
    "total_price": 12500.00,
    "cash": "7000.00",
    "credit": "5500.00",
    "received": "7000.00",
    "pending": "5500.00",
    "delivery_date": "2025-08-20"
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Order updated successfully!",
    "data": {
        "id": 1,
        "order_id": "ORD-2025-001",
        "user_id": "USER123",
        "products": [
            {
                "product_id": 1,
                "reflector_color": "White",
                "location": ["Mumbai", "Delhi", "Pune"],
                "qty": 5,
                "price": 2500.00
            }
        ],
        "total_price": "12500.00",
        "status": 2,
        "cash": "7000.00",
        "credit": "5500.00",
        "received": "7000.00",
        "pending": "5500.00",
        "customer_id": 1,
        "delivery_date": "2025-08-20",
        "created_at": "2025-07-31T10:30:00.000000Z",
        "updated_at": "2025-07-31T10:35:00.000000Z",
        "customer": {...}
    }
}
```

---

### 5. **GET** - Get Orders by Customer ID
**URL:** `GET /api/order-details/customer/{customer_id}`

**Example:** `GET /api/order-details/customer/1`

**Expected Response:**
```json
{
    "success": true,
    "message": "Orders retrieved successfully!",
    "data": [
        {
            "id": 1,
            "order_id": "ORD-2025-001",
            "user_id": "USER123",
            "products": [...],
            "total_price": "12500.00",
            "status": 2,
            "cash": "7000.00",
            "credit": "5500.00",
            "received": "7000.00",
            "pending": "5500.00",
            "customer_id": 1,
            "delivery_date": "2025-08-20",
            "created_at": "2025-07-31T10:30:00.000000Z",
            "updated_at": "2025-07-31T10:35:00.000000Z",
            "customer": {...}
        }
    ]
}
```

---

### 6. **PUT** - Update Order by Order ID
**URL:** `PUT /api/order-details/order/{order_id}`

**Example:** `PUT /api/order-details/order/ORD-2025-001`

```json
{
    "status": 3,
    "products": [
        {
            "product_id": 1,
            "reflector_color": "White",
            "location": ["Mumbai"],
            "qty": 3,
            "price": 2500.00
        },
        {
            "product_id": 3,
            "reflector_color": "Black",
            "location": ["Delhi", "Bangalore"],
            "qty": 1,
            "price": 3000.00
        }
    ],
    "total_price": 10500.00,
    "cash": "8000.00",
    "credit": "2500.00",
    "received": "8000.00",
    "pending": "2500.00",
    "delivery_date": "2025-08-25"
}
```

---

### 7. **GET** - Get Order by ID
**URL:** `GET /api/order-details/{id}`

**Example:** `GET /api/order-details/1`

---

### 8. **GET** - Get Orders by User ID
**URL:** `GET /api/order-details/user/{user_id}`

**Example:** `GET /api/order-details/user/USER123`

---

### 9. **PUT** - Update Order by ID
**URL:** `PUT /api/order-details/{id}`

**Example:** `PUT /api/order-details/1`

```json
{
    "status": 4,
    "total_price": 12000.00,
    "cash": "10000.00",
    "credit": "2000.00",
    "received": "10000.00",
    "pending": "2000.00",
    "delivery_date": "2025-08-30"
}
```

---

### 10. **DELETE** - Delete Order
**URL:** `DELETE /api/order-details/{order_id}`

**Example:** `DELETE /api/order-details/ORD-2025-001`

**Expected Response:**
```json
{
    "success": true,
    "message": "Oldest order with order_id deleted successfully!"
}
```

---

## 🔧 **Postman Setup Tips**

1. **Base URL:** `http://your-domain.com/api`
2. **Headers:** 
   - `Content-Type: application/json`
   - `Accept: application/json`
3. **Authentication:** Add if required
4. **Test Data:** Ensure you have:
   - Valid `customer_id` (exists in `architects` table)
   - Valid `product_id` (exists in `add_product` table)

---

## 📝 **Validation Rules**

- `order_id`: Required, string, max 255 characters
- `user_id`: Optional, string
- `products`: Required array
- `products.*.product_id`: Required, integer, exists in add_product table
- `products.*.reflector_color`: Optional, string
- `products.*.location`: Optional, array of strings
- `products.*.qty`: Required, integer
- `products.*.price`: Required, numeric
- `total_price`: Optional, numeric (auto-calculated if not provided)
- `status`: Optional, integer
- `cash`, `credit`, `received`, `pending`: Optional, string, max 255
- `customer_id`: Required, integer, exists in architects table
- `delivery_date`: Optional, date format (YYYY-MM-DD)

---

## ✅ **Key Features**

✅ **Simplified Structure**: Only essential fields
✅ **Foreign Key Relationship**: Links to architects table
✅ **Auto Calculation**: Total price calculated automatically
✅ **Array Support**: Location field as array
✅ **Eager Loading**: Customer data included in responses
✅ **Validation**: Comprehensive input validation
✅ **Error Handling**: Proper error responses 