<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập bị từ chối</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
            text-align: center;
        }
        .error-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        .error-icon {
            font-size: 100px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-code {
            font-size: 72px;
            color: #e74c3c;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .error-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .error-description {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .back-link {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🚫</div>
        <div class="error-code">403</div>
        <div class="error-message">Truy cập bị từ chối</div>
        <div class="error-description">
            Bạn không có quyền truy cập vào trang này. 
            Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là một lỗi.
        </div>
        <a href="<?php echo e(url('/')); ?>" class="back-link">Quay về trang chủ</a>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\laravel-php3\example-app\resources\views/errors/no-access.blade.php ENDPATH**/ ?>