<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>棚卸し管理システム</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .progress-circle {
            transform: rotate(-90deg);
        }
        .progress-circle-bg {
            stroke: #e5e7eb;
        }
        .progress-circle-fill {
            stroke: #3b82f6;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.3s ease-in-out;
        }
        .alert-banner {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }
        
        /* カスタムグラデーション */
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .gradient-warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        /* カードホバー効果 */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* ボタンホバー効果 */
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* アイコンアニメーション */
        .icon-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen p-6">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full gradient-primary mb-4">
                    <i data-lucide="package" class="h-8 w-8 text-white"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">棚卸し管理システム</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">棚卸しの日を"なんとか終わる日"から"普通の業務日"に変える</p>
            </div>

            <!-- Main Dashboard -->
            <div class="space-y-8">
                <!-- タブナビゲーション -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button id="preparation-tab" class="border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600 whitespace-nowrap" onclick="showTab('preparation')">
                            <i data-lucide="compass" class="inline-block w-4 h-4 mr-2"></i>
                            棚卸前 - 準備進捗率
                        </button>
                        <button id="progress-tab" class="border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap" onclick="showTab('progress')">
                            <i data-lucide="trending-up" class="inline-block w-4 h-4 mr-2"></i>
                            棚卸中 - 進行状況
                        </button>
                    </nav>
                </div>

                <!-- 開始前（準備進捗率）セクション -->
                <div id="preparation-content" class="rounded-lg border bg-white shadow-sm">
                    <div class="border-b p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                            <i data-lucide="compass" class="h-6 w-6 text-blue-600"></i>
                            開始前 - 準備進捗率
                        </h2>
                        <p class="mt-2 text-gray-600">棚卸し開始前の準備状況を確認</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- 旅のしおり風の進捗表示 -->
                        <div class="mb-8">
                            <div class="flex items-center justify-center py-8">
                                <div class="relative" style="width: 150px; height: 150px;">
                                    <svg width="150" height="150" class="progress-circle">
                                        <circle
                                            cx="75"
                                            cy="75"
                                            r="70"
                                            stroke="currentColor"
                                            stroke-width="12"
                                            fill="transparent"
                                            class="progress-circle-bg"
                                        />
                                        <circle
                                            cx="75"
                                            cy="75"
                                            r="70"
                                            stroke="currentColor"
                                            stroke-width="12"
                                            fill="transparent"
                                            stroke-dasharray="440"
                                            stroke-dashoffset="132"
                                            class="progress-circle-fill"
                                        />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-blue-600">70%</div>
                                            <div class="text-sm text-gray-500">準備完了</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- チェックリストカード -->
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 mb-8">
                            <?php
                            $checklistItems = [
                                [
                                    'title' => 'エリア設定',
                                    'description' => '棚卸しエリアの定義と整理',
                                    'icon' => 'map-pin',
                                    'status' => '完了',
                                    'completed' => true
                                ],
                                [
                                    'title' => '人員登録',
                                    'description' => 'チームメンバーの追加と役割割り当て',
                                    'icon' => 'users',
                                    'status' => '完了',
                                    'completed' => true
                                ],
                                [
                                    'title' => 'スケジュール設定',
                                    'description' => '棚卸しの日程と時間の設定',
                                    'icon' => 'calendar',
                                    'status' => '進行中',
                                    'completed' => false
                                ],
                                [
                                    'title' => '権限設定',
                                    'description' => '役割別のアクセス権限設定',
                                    'icon' => 'shield',
                                    'status' => '完了',
                                    'completed' => true
                                ],
                                [
                                    'title' => 'システム設定',
                                    'description' => 'アプリケーション設定の確認',
                                    'icon' => 'settings',
                                    'status' => '完了',
                                    'completed' => true
                                ],
                                [
                                    'title' => '最終確認',
                                    'description' => '開始前の最終チェック',
                                    'icon' => 'check-circle',
                                    'status' => '未完了',
                                    'completed' => false
                                ]
                            ];

                            foreach ($checklistItems as $item): ?>
                                <div class="rounded-lg border bg-white text-gray-900 shadow-sm <?php echo $item['completed'] ? 'border-green-200 bg-green-50' : ''; ?>">
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 rounded-lg <?php echo $item['completed'] ? 'bg-green-100' : 'bg-gray-100'; ?>">
                                                    <i data-lucide="<?php echo $item['icon']; ?>" class="h-5 w-5 <?php echo $item['completed'] ? 'text-green-600' : 'text-gray-600'; ?>"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold"><?php echo $item['title']; ?></h3>
                                                    <p class="text-sm text-gray-600"><?php echo $item['description']; ?></p>
                                                </div>
                                            </div>
                                            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold <?php echo $item['completed'] ? 'border-transparent bg-green-600 text-white' : 'border-transparent bg-gray-100 text-gray-600'; ?>">
                                                <?php if ($item['completed']): ?>
                                                    <i data-lucide="check-circle-2" class="mr-1 h-3 w-3"></i>
                                                <?php else: ?>
                                                    <i data-lucide="clock" class="mr-1 h-3 w-3"></i>
                                                <?php endif; ?>
                                                <?php echo $item['status']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- エリア設定状況 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="map" class="h-5 w-5"></i>
                                エリア設定状況
                            </h3>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium">エリアA - 食品</span>
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            <i data-lucide="check" class="mr-1 h-3 w-3"></i>
                                            設定済
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium">エリアB - 衣類</span>
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            <i data-lucide="check" class="mr-1 h-3 w-3"></i>
                                            設定済
                                        </span>
                                    </div>
                                </div>
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium">エリアC - 電子機器</span>
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <i data-lucide="x" class="mr-1 h-3 w-3"></i>
                                            未設定
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 人員登録状況（戦闘力アイコン付き） -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold flex items-center gap-2">
                                    <i data-lucide="users" class="h-5 w-5"></i>
                                    人員登録状況
                                </h3>
                                <a href="register.php" class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                                    <i data-lucide="user-plus" class="mr-2 h-4 w-4"></i>
                                    出勤人員登録
                                </a>
                            </div>
                            
                            <!-- サマリ情報 -->
                            <?php
                            require_once("funcs.php");
                            $pdo = db_conn();
                            
                            // 統計情報を取得
                            $statsStmt = $pdo->prepare('SELECT COUNT(*) as total_members, SUM(work_hours) as total_work_hours FROM member_table;');
                            $statsStmt->execute();
                            $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
                            
                            // 労働時間合計を時間と分に変換
                            $totalWorkHours = floatval($stats['total_work_hours']);
                            $totalHours = floor($totalWorkHours);
                            $totalMinutes = round(($totalWorkHours - $totalHours) * 60);
                            $totalWorkTimeDisplay = $totalHours . '時間 ' . $totalMinutes . '分';
                            ?>
                            
                            <div class="grid gap-6 md:grid-cols-2 mb-8">
                                <div class="rounded-xl border-0 bg-white p-6 shadow-lg card-hover">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 rounded-xl gradient-primary">
                                            <i data-lucide="users" class="h-6 w-6 text-white"></i>
                                        </div>
                                        <div>
                                            <div class="text-3xl font-bold text-gray-900"><?= $stats['total_members'] ?></div>
                                            <div class="text-sm text-gray-600 font-medium">登録人員数</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-xl border-0 bg-white p-6 shadow-lg card-hover">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 rounded-xl gradient-success">
                                            <i data-lucide="clock" class="h-6 w-6 text-white"></i>
                                        </div>
                                        <div>
                                            <div class="text-3xl font-bold text-gray-900"><?= $totalWorkTimeDisplay ?></div>
                                            <div class="text-sm text-gray-600 font-medium">労働時間合計</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 登録済み一覧ボタン -->
                            <div class="flex justify-center">
                                <a href="read.php" class="inline-flex items-center justify-center rounded-xl gradient-secondary px-8 py-4 text-sm font-semibold text-white btn-hover shadow-lg">
                                    <i data-lucide="list" class="mr-2 h-5 w-5"></i>
                                    登録済み一覧を表示
                                </a>
                            </div>
                        </div>

                        <!-- スケジュール初期案（AIドラフトボタン） -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="calendar" class="h-5 w-5"></i>
                                スケジュール初期案
                            </h3>
                            <div class="rounded-lg border bg-white p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="font-medium">推奨スケジュール</h4>
                                        <p class="text-sm text-gray-600">AIが最適化した棚卸しスケジュール</p>
                                    </div>
                                    <button class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                        <i data-lucide="sparkles" class="mr-2 h-4 w-4"></i>
                                        AIドラフト生成
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">エリアA - 食品</span>
                                            <p class="text-sm text-gray-600">9:00 - 11:00 (2時間)</p>
                                        </div>
                                        <span class="text-sm text-gray-500">3名</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">エリアB - 衣類</span>
                                            <p class="text-sm text-gray-600">11:00 - 12:30 (1.5時間)</p>
                                        </div>
                                        <span class="text-sm text-gray-500">2名</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">エリアC - 電子機器</span>
                                            <p class="text-sm text-gray-600">13:30 - 15:00 (1.5時間)</p>
                                        </div>
                                        <span class="text-sm text-gray-500">4名</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 権限設定（役割別タイル表示） -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="shield" class="h-5 w-5"></i>
                                権限設定
                            </h3>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="p-2 rounded-lg bg-red-100">
                                            <i data-lucide="crown" class="h-5 w-5 text-red-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">管理者</h4>
                                            <p class="text-sm text-gray-600">全権限</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span>エリア管理</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>ユーザー管理</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>レポート閲覧</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="p-2 rounded-lg bg-blue-100">
                                            <i data-lucide="user-check" class="h-5 w-5 text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">リーダー</h4>
                                            <p class="text-sm text-gray-600">エリア管理</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span>エリア管理</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>ユーザー管理</span>
                                            <i data-lucide="x" class="h-4 w-4 text-gray-400"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>レポート閲覧</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-lg border bg-white p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="p-2 rounded-lg bg-green-100">
                                            <i data-lucide="user" class="h-5 w-5 text-green-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">メンバー</h4>
                                            <p class="text-sm text-gray-600">基本操作</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span>エリア管理</span>
                                            <i data-lucide="x" class="h-4 w-4 text-gray-400"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>ユーザー管理</span>
                                            <i data-lucide="x" class="h-4 w-4 text-gray-400"></i>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span>レポート閲覧</span>
                                            <i data-lucide="check" class="h-4 w-4 text-green-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 棚卸し中（進行状況）セクション -->
                <div id="progress-content" class="rounded-lg border bg-white shadow-sm hidden">
                    <div class="border-b p-6">
                        <h2 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
                            <i data-lucide="trending-up" class="h-6 w-6 text-green-600"></i>
                            棚卸し中 - 進行状況
                        </h2>
                        <p class="mt-2 text-gray-600">現在の棚卸しプロセスの進行状況</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- 遅延アラート（赤帯） -->
                        <div class="alert-banner rounded-lg p-4 mb-6 text-white">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="h-5 w-5"></i>
                                <div>
                                    <h4 class="font-medium">遅延アラート</h4>
                                    <p class="text-sm opacity-90">エリアB - 衣類が予定より30分遅れています</p>
                                </div>
                                <button class="ml-auto inline-flex items-center justify-center rounded-md bg-white/20 px-3 py-1 text-sm font-medium hover:bg-white/30">
                                    詳細確認
                                </button>
                            </div>
                        </div>

                        <!-- エリア別進捗バー -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="bar-chart-3" class="h-5 w-5"></i>
                                エリア別進捗
                            </h3>
                            <div class="space-y-4">
                                <?php
                                $areaProgress = [
                                    [
                                        'name' => 'エリアA - 食品',
                                        'progress' => 85,
                                        'status' => '順調',
                                        'members' => 3,
                                        'estimatedTime' => '30分'
                                    ],
                                    [
                                        'name' => 'エリアB - 衣類',
                                        'progress' => 45,
                                        'status' => '遅延',
                                        'members' => 2,
                                        'estimatedTime' => '1時間'
                                    ],
                                    [
                                        'name' => 'エリアC - 電子機器',
                                        'progress' => 95,
                                        'status' => 'ほぼ完了',
                                        'members' => 4,
                                        'estimatedTime' => '10分'
                                    ]
                                ];

                                foreach ($areaProgress as $area): ?>
                                    <div class="rounded-lg border bg-white p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <h4 class="font-medium"><?php echo $area['name']; ?></h4>
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $area['status'] === '遅延' ? 'bg-red-100 text-red-800' : ($area['status'] === 'ほぼ完了' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                                    <?php echo $area['status']; ?>
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600"><?php echo $area['members']; ?>名</div>
                                                <div class="text-xs text-gray-500">残り<?php echo $area['estimatedTime']; ?></div>
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="h-3 rounded-full transition-all duration-300 <?php echo $area['status'] === '遅延' ? 'bg-red-500' : ($area['status'] === 'ほぼ完了' ? 'bg-green-500' : 'bg-blue-500'); ?>" style="width: <?php echo $area['progress']; ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600 mt-1">
                                            <span>進捗</span>
                                            <span><?php echo $area['progress']; ?>%</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 担当者別の進捗カード -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="users" class="h-5 w-5"></i>
                                担当者別進捗
                            </h3>
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <?php
                                $memberProgress = [
                                    [
                                        'name' => '田中太郎',
                                        'role' => 'リーダー',
                                        'area' => 'エリアA',
                                        'progress' => 90,
                                        'status' => '順調',
                                        'avatar' => '👨‍💼'
                                    ],
                                    [
                                        'name' => '佐藤花子',
                                        'role' => 'メンバー',
                                        'area' => 'エリアA',
                                        'progress' => 85,
                                        'status' => '順調',
                                        'avatar' => '👩‍💼'
                                    ],
                                    [
                                        'name' => '鈴木一郎',
                                        'role' => 'メンバー',
                                        'area' => 'エリアB',
                                        'progress' => 40,
                                        'status' => '遅延',
                                        'avatar' => '👨‍💻'
                                    ],
                                    [
                                        'name' => '高橋美咲',
                                        'role' => 'メンバー',
                                        'area' => 'エリアB',
                                        'progress' => 50,
                                        'status' => '遅延',
                                        'avatar' => '👩‍💻'
                                    ],
                                    [
                                        'name' => '山田次郎',
                                        'role' => 'メンバー',
                                        'area' => 'エリアC',
                                        'progress' => 95,
                                        'status' => '完了',
                                        'avatar' => '👨‍🔧'
                                    ],
                                    [
                                        'name' => '伊藤三郎',
                                        'role' => 'メンバー',
                                        'area' => 'エリアC',
                                        'progress' => 100,
                                        'status' => '完了',
                                        'avatar' => '👨‍🏭'
                                    ]
                                ];

                                foreach ($memberProgress as $member): ?>
                                    <div class="rounded-lg border bg-white p-4">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="text-2xl"><?php echo $member['avatar']; ?></div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-sm"><?php echo $member['name']; ?></h4>
                                                <p class="text-xs text-gray-600"><?php echo $member['role']; ?> - <?php echo $member['area']; ?></p>
                                            </div>
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium <?php echo $member['status'] === '遅延' ? 'bg-red-100 text-red-800' : ($member['status'] === '完了' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                                <?php echo $member['status']; ?>
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="h-2 rounded-full transition-all duration-300 <?php echo $member['status'] === '遅延' ? 'bg-red-500' : ($member['status'] === '完了' ? 'bg-green-500' : 'bg-blue-500'); ?>" style="width: <?php echo $member['progress']; ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span>進捗</span>
                                            <span><?php echo $member['progress']; ?>%</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- 残作業見込み時間表示 -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                <i data-lucide="clock" class="h-5 w-5"></i>
                                残作業見込み時間
                            </h3>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-lg border bg-white p-4 text-center">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">2.5時間</div>
                                    <div class="text-sm text-gray-600">全体の残り時間</div>
                                </div>
                                <div class="rounded-lg border bg-white p-4 text-center">
                                    <div class="text-3xl font-bold text-orange-600 mb-2">1時間</div>
                                    <div class="text-sm text-gray-600">最長エリア（エリアB）</div>
                                </div>
                                <div class="rounded-lg border bg-white p-4 text-center">
                                    <div class="text-3xl font-bold text-green-600 mb-2">15:30</div>
                                    <div class="text-sm text-gray-600">予想完了時刻</div>
                                </div>
                            </div>
                        </div>

                        <!-- 「進捗を共有」ボタン -->
                        <div class="text-center">
                            <button class="inline-flex items-center justify-center rounded-md bg-green-600 px-6 py-3 text-sm font-medium text-white hover:bg-green-700">
                                <i data-lucide="share-2" class="mr-2 h-4 w-4"></i>
                                進捗を共有
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Tab functionality
        function showTab(tabName) {
            // Hide all content
            document.getElementById('preparation-content').classList.add('hidden');
            document.getElementById('progress-content').classList.add('hidden');
            
            // Remove active state from all tabs
            document.getElementById('preparation-tab').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('preparation-tab').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('progress-tab').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('progress-tab').classList.add('border-transparent', 'text-gray-500');
            
            // Show selected content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active state to selected tab
            document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById(tabName + '-tab').classList.add('border-blue-500', 'text-blue-600');
        }

        // Initialize with preparation tab active
        showTab('preparation');
    </script>
</body>
</html> 