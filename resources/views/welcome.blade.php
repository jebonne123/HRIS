<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRIS - Human Resource Information System</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .btn {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 50px;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .status {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 HRIS</h1>
        <p>Human Resource Information System</p>
        
        <div class="status">
            <h3>System Status</h3>
            <p>✅ Laravel Backend: Running</p>
            <p>✅ Database: Connected</p>
            <p>✅ Vue Frontend: Available at <a href="http://localhost:5173" style="color: #fff; text-decoration: underline;">http://localhost:5173</a></p>
        </div>

        <div>
            <a href="http://localhost:5173" class="btn">🚀 Launch Frontend</a>
            <a href="/api" class="btn">🔌 API Endpoints</a>
        </div>

        <div style="margin-top: 3rem; opacity: 0.7; font-size: 0.9rem;">
            <p>Built with Laravel 10, Vue 3, and MySQL</p>
            <p>Default Admin: admin@hris.com / password</p>
        </div>
    </div>
</body>
</html>




