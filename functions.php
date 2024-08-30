<?php
include 'config.php';

session_start();

function login($username, $password) {
    
    global $conn;

    // Periksa apakah persiapan statement berhasil
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameter dan eksekusi statement
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Periksa apakah username ada
    if ($stmt->num_rows === 0) {
        $stmt->close();
        return false;
    }

    // Bind hasil dan verifikasi password
    $stmt->bind_result($hashedPassword, $role);
    $stmt->fetch();
    $stmt->close();

    // Verifikasi password
    if (password_verify($password, $hashedPassword)) {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; // Simpan peran ke dalam sesi

        return true;
    } else {
        return false;
    }
}

function register($username, $password) {
    global $conn; 

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'customer')");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $username, $hashedPassword);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: auth/login.php');
    exit();
}

function isAuthenticated() {
    return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin';
}

function isCustomer() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer';
}

function getProducts() {
    global $conn;
    $result = $conn->query("SELECT * FROM products");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getProduct($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function addProduct($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        
        // Cek apakah direktori images ada
        $targetDir = "../assets/images/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Upload file gambar
        $targetFile = $targetDir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sdss", $name, $price, $description, $image);
                
                if ($stmt->execute()) {
                    echo "Product added successfully.";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    }
}


function updateProduct($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];

        $updateQuery = "UPDATE products SET name = ?, price = ?, description = ?";
        if ($image) {
            // Upload file gambar
            $target = "images/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $updateQuery .= ", image = ?";
        }
        $updateQuery .= " WHERE id = ?";

        $stmt = $conn->prepare($updateQuery);
        if ($image) {
            $stmt->bind_param("sdssi", $name, $price, $description, $image, $id);
        } else {
            $stmt->bind_param("sdsi", $name, $price, $description, $id);
        }
        
        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header("Location: index.php");
                    exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

function deleteProduct($conn) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch current product to get the image
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Delete the product record
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete the product image from the server if it exists
            if ($product['image']) {
                $imagePath = "images/" . $product['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            echo "Product deleted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fungsi untuk menambahkan produk ke keranjang
function addToCart($productId, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Fungsi untuk menghapus produk dari keranjang
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Fungsi untuk mendapatkan isi keranjang
function getCartItems() {
    if (!isset($_SESSION['cart'])) {
        return array();
    }
    return $_SESSION['cart'];
}


// Menghitung jumlah total
function calculateTotalAmount($cartItems) {
    $totalAmount = 0;
    foreach ($cartItems as $productId => $quantity) {
        $product = getProduct($productId);
        $totalAmount += $product['price'] * $quantity;
    }
    return $totalAmount;
}

// Simpan pesanan ke database (Contoh sederhana)
function saveOrder($cartItems, $totalAmount) {
    global $conn; // Pastikan $conn adalah koneksi database global
    $name = $_POST['name'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment_method'];

    // Simpan pesanan ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (name, address, payment_method, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('sssd', $name, $address, $paymentMethod, $totalAmount);
    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;

        // Simpan item pesanan ke tabel order_items
        foreach ($cartItems as $productId => $quantity) {
            $product = getProduct($productId);
            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmtItem->bind_param('iiid', $orderId, $productId, $quantity, $product['price']);
            $stmtItem->execute();
            $stmtItem->close();
        }
        
        $stmt->close();
        return $orderId;
    } else {
        return false;
    }
}


// Kosongkan keranjang setelah checkout
function clearCart() {
    unset($_SESSION['cart']);
}
