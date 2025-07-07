<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>出勤人員登録</title>
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2em;
        }
        
        .nav {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .nav a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        
        .nav a:hover {
            background-color: #e9ecef;
        }
        
        .content {
            padding: 20px;
        }
        
        .member-form {
            border: 1px solid #dee2e6;
            padding: 24px;
            margin: 20px 0;
            border-radius: 12px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .time-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .time-input {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .am-pm-buttons {
            display: flex;
            gap: 5px;
        }
        .am-pm-btn {
            padding: 5px 10px;
            border: 1px solid #ccc;
            background: #f0f0f0;
            cursor: pointer;
        }
        .am-pm-btn.active {
            background: #007bff;
            color: white;
        }
        .work-hours {
            font-weight: bold;
            color: #007bff;
            margin: 10px 0;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 4px;
        }
        .button-group {
            display: flex;
            gap: 12px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .add-member-btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
        }
        .add-member-btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        .remove-member-btn {
            background: #6c757d;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            float: right;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }
        .remove-member-btn:hover {
            background: #545b62;
            transform: translateY(-1px);
        }
        .submit-btn {
            background: #28a745;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        }
        .submit-btn:hover {
            background: #1e7e34;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

    </style>
</head>

<body>


    <div class="container">
        <div class="header">
            <h1>出勤人員登録</h1>
        </div>
        
        <div class="nav">
            <a href="read.php">登録済み一覧</a>
            <a href="index.php">ダッシュボード</a>
        </div>
        
        <div class="content">
            <form method="POST" action="create.php" id="memberForm">
                <div id="membersContainer">
                    <!-- メンバーフォームがここに動的に追加されます -->
                </div>
                
                <div class="button-group">
                    <button type="button" class="add-member-btn" onclick="addMember()">
                        + メンバーを追加
                    </button>
                    
                    <button type="submit" class="submit-btn">
                        全員を登録
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let memberCount = 0;

        // 初期メンバーを追加
        window.onload = function() {
            addMember();
        };

        function addMember() {
            memberCount++;
            const container = document.getElementById('membersContainer');
            
            const memberDiv = document.createElement('div');
            memberDiv.className = 'member-form';
            memberDiv.id = 'member-' + memberCount;
            
            memberDiv.innerHTML = `
                <button type="button" class="remove-member-btn" onclick="removeMember(${memberCount})">削除</button>
                <h3>メンバー ${memberCount}</h3>
                
                <div>
                    <label>名前：<input type="text" name="members[${memberCount}][name]" required></label>
                </div>
                
                <div>
                    <label>勤務開始日：<input type="date" name="members[${memberCount}][start_date]" required></label>
                </div>
                
                <div class="time-input-group">
                    <label>勤務開始時間：</label>
                    <div class="time-input">
                        <select name="members[${memberCount}][start_hour]">
                            ${generateHourOptions()}
                        </select>
                        <span>:</span>
                        <select name="members[${memberCount}][start_minute]">
                            ${generateMinuteOptions()}
                        </select>
                        <div class="am-pm-buttons">
                            <button type="button" class="am-pm-btn active" data-member="${memberCount}" data-type="start" data-period="AM" onclick="setAMPM(this)">AM</button>
                            <button type="button" class="am-pm-btn" data-member="${memberCount}" data-type="start" data-period="PM" onclick="setAMPM(this)">PM</button>
                        </div>
                        <input type="hidden" name="members[${memberCount}][start_period]" value="AM">
                    </div>
                </div>
                
                <div>
                    <label>勤務終了日：<input type="date" name="members[${memberCount}][end_date]" required></label>
                </div>
                
                <div class="time-input-group">
                    <label>勤務終了時間：</label>
                    <div class="time-input">
                        <select name="members[${memberCount}][end_hour]">
                            ${generateHourOptions()}
                        </select>
                        <span>:</span>
                        <select name="members[${memberCount}][end_minute]">
                            ${generateMinuteOptions()}
                        </select>
                        <div class="am-pm-buttons">
                            <button type="button" class="am-pm-btn active" data-member="${memberCount}" data-type="end" data-period="AM" onclick="setAMPM(this)">AM</button>
                            <button type="button" class="am-pm-btn" data-member="${memberCount}" data-type="end" data-period="PM" onclick="setAMPM(this)">PM</button>
                        </div>
                        <input type="hidden" name="members[${memberCount}][end_period]" value="AM">
                    </div>
                </div>
                
                                    <div>
                        <label>休憩時間：</label>
                        <select name="members[${memberCount}][breaktime]">
                            ${generateBreakTimeOptions()}
                        </select>
                    </div>
                

            `;
            
            container.appendChild(memberDiv);
            
            // 今日の日付をデフォルトに設定
            const today = new Date().toISOString().split('T')[0];
            memberDiv.querySelector('input[name="members[' + memberCount + '][start_date]"]').value = today;
            memberDiv.querySelector('input[name="members[' + memberCount + '][end_date]"]').value = today;
        }

        function removeMember(memberId) {
            const memberDiv = document.getElementById('member-' + memberId);
            if (memberDiv) {
                memberDiv.remove();
            }
        }

        function generateHourOptions() {
            let options = '';
            for (let i = 1; i <= 12; i++) {
                options += `<option value="${i}">${i}</option>`;
            }
            return options;
        }

        function generateMinuteOptions() {
            let options = '';
            for (let i = 0; i < 60; i += 30) {
                const minute = i.toString().padStart(2, '0');
                options += `<option value="${minute}">${minute}</option>`;
            }
            return options;
        }

        function generateBreakTimeOptions() {
            let options = '';
            for (let i = 0; i <= 480; i += 15) {
                const hours = Math.floor(i / 60);
                const minutes = i % 60;
                let displayText = '';
                if (hours > 0) {
                    displayText = hours + '時間';
                    if (minutes > 0) {
                        displayText += minutes + '分';
                    }
                } else {
                    displayText = minutes + '分';
                }
                const selected = (i === 0) ? 'selected' : '';
                options += `<option value="${i}" ${selected}>${displayText}</option>`;
            }
            return options;
        }

        function setAMPM(button) {
            const memberId = button.dataset.member;
            const type = button.dataset.type;
            const period = button.dataset.period;
            
            // 同じグループのボタンのアクティブ状態を切り替え
            const buttons = button.parentElement.querySelectorAll('.am-pm-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // 隠しフィールドを更新
            const hiddenField = button.parentElement.parentElement.querySelector(`input[name="members[${memberId}][${type}_period]"]`);
            hiddenField.value = period;
        }


    </script>
</body>

</html>
