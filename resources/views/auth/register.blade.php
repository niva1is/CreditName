<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация компании | Альфа-Бизнес</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F5ECC, #020617);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { 
            font-size: 28px; 
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .subtitle { color: #6B7280; margin-bottom: 32px; line-height: 1.5; }
        .section {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #E5E7EB;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #0F172A;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #0F5ECC;
            box-shadow: 0 0 0 3px rgba(15,94,204,0.1);
        }
        .form-group .input-hint {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 4px;
        }
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1D4ED8, #0F5ECC);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(15,94,204,0.3);
        }
        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6B7280;
        }
        .login-link a { 
            color: #0F5ECC; 
            text-decoration: none; 
            font-weight: 500; 
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error { 
            color: #DC2626; 
            font-size: 12px; 
            margin-top: 4px; 
        }
        
        /* 👇 СТИЛИ ДЛЯ ЧЕКБОКСОВ */
        .agreement-section {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .agreement-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }
        .agreement-item:last-child {
            margin-bottom: 0;
        }
        .agreement-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            min-width: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #0F5ECC;
        }
        .agreement-item label {
            font-size: 13px;
            line-height: 1.5;
            color: #374151;
            cursor: pointer;
            font-weight: 400;
        }
        .agreement-item a {
            color: #0F5ECC;
            text-decoration: underline;
            font-weight: 500;
        }
        .agreement-item a:hover {
            color: #1D4ED8;
        }
        .error-message {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .required-mark {
            color: #DC2626;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
            .container { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Регистрация компании</h1>
        <p class="subtitle">
            Заполните данные о юридическом лице для открытия доступа к кредитным продуктам банка.<br>
            После проверки менеджером вы получите доступ в личный кабинет.
        </p>

        @if($errors->any())
            <div class="error-message">
                ⚠️ Пожалуйста, исправьте ошибки в форме.
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
            @csrf
            
            <!-- Данные компании -->
            <div class="section">
                <div class="section-title">📋 Данные компании</div>
                
                <div class="form-group">
                    <label>Полное наименование <span class="required-mark">*</span></label>
                    <input type="text" name="company_full_name" 
                           value="{{ old('company_full_name') }}" 
                           placeholder="Общество с ограниченной ответственностью «ТехИнвестСтрой»"
                           required>
                    @error('company_full_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Краткое наименование <span class="required-mark">*</span></label>
                    <input type="text" name="company_short_name" 
                           value="{{ old('company_short_name') }}" 
                           placeholder="ООО «ТехИнвестСтрой»"
                           required>
                    @error('company_short_name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>ИНН <span class="required-mark">*</span></label>
                        <input type="text" name="inn" 
                               value="{{ old('inn') }}" 
                               placeholder="771234567890"
                               maxlength="12" required>
                        <div class="input-hint">12 цифр</div>
                        @error('inn') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>ОГРН <span class="required-mark">*</span></label>
                        <input type="text" name="ogrn" 
                               value="{{ old('ogrn') }}" 
                               placeholder="1234567890123"
                               maxlength="13" required>
                        <div class="input-hint">13 цифр</div>
                        @error('ogrn') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Форма собственности <span class="required-mark">*</span></label>
                    <select name="ownership_form" required>
                        <option value="">— Выберите форму собственности —</option>
                        <option value="ООО" {{ old('ownership_form') == 'ООО' ? 'selected' : '' }}>ООО — Общество с ограниченной ответственностью</option>
                        <option value="АО" {{ old('ownership_form') == 'АО' ? 'selected' : '' }}>АО — Акционерное общество</option>
                        <option value="ПАО" {{ old('ownership_form') == 'ПАО' ? 'selected' : '' }}>ПАО — Публичное акционерное общество</option>
                        <option value="ЗАО" {{ old('ownership_form') == 'ЗАО' ? 'selected' : '' }}>ЗАО — Закрытое акционерное общество</option>
                    </select>
                    @error('ownership_form') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Юридический адрес <span class="required-mark">*</span></label>
                    <textarea name="legal_address" rows="2" 
                              placeholder="125315, г. Москва, ул. Балтийская, д. 12, стр. 1, офис 305"
                              required>{{ old('legal_address') }}</textarea>
                    @error('legal_address') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Телефон <span class="required-mark">*</span></label>
                        <input type="text" name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="+7 (495) 123-45-67"
                               required>
                    </div>
                    <div class="form-group">
                        <label>Контактное лицо (ФИО) <span class="required-mark">*</span></label>
                        <input type="text" name="contact_person" 
                               value="{{ old('contact_person') }}" 
                               placeholder="Иванов Пётр Александрович"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email для связи <span class="required-mark">*</span></label>
                    <input type="email" name="contact_email" 
                           value="{{ old('contact_email') }}" 
                           placeholder="info@company.ru"
                           required>
                </div>
            </div>

            <!-- Загрузка подтверждающих документов -->
            <div class="section">
                <div class="section-title">📎 Подтверждающие документы</div>
                <p style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">
                    Загрузите сканы или фотографии документов (PDF, JPG, PNG, DOC, DOCX до 10 МБ)
                </p>
                
                <!-- Drag & Drop зона -->
                <div id="dropZone" style="
                    border: 2px dashed #CBD5E1;
                    border-radius: 16px;
                    padding: 40px;
                    text-align: center;
                    background: #FAFBFC;
                    cursor: pointer;
                    transition: all 0.3s;
                    margin-bottom: 16px;
                " 
                ondrop="handleDrop(event)" 
                ondragover="handleDragOver(event)" 
                ondragleave="handleDragLeave(event)"
                onclick="document.getElementById('fileInput').click()">
                    <div style="font-size: 48px; margin-bottom: 12px;">📤</div>
                    <div style="font-size: 16px; font-weight: 500; color: #374151;">Перетащите файлы сюда</div>
                    <div style="font-size: 13px; color: #9CA3AF; margin-top: 8px;">или нажмите для выбора</div>
                    <input type="file" id="fileInput" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" onchange="handleFiles(this.files)">
                </div>
                
                <!-- Список загруженных файлов -->
                <div id="uploadedFiles" style="display: flex; flex-direction: column; gap: 8px;">
                    <!-- Файлы будут добавляться сюда динамически -->
                </div>
                
                <!-- Скрытые поля для типов документов -->
                <div id="documentTypesContainer"></div>
                
                <div style="font-size: 12px; color: #9CA3AF; margin-top: 12px;">
                    Максимум 10 файлов | Размер каждого до 10 МБ | Форматы: PDF, JPG, PNG, DOC, DOCX
                </div>
            </div>

            <style>
                #dropZone:hover {
                    border-color: #0F5ECC;
                    background: #F0F7FF;
                }
                #dropZone.drag-over {
                    border-color: #0F5ECC;
                    background: #EFF6FF;
                    transform: scale(1.02);
                }
                .file-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 16px;
                    background: #F9FAFB;
                    border-radius: 12px;
                    border: 1px solid #E5E7EB;
                }
                .file-item .file-name {
                    flex: 1;
                    font-size: 14px;
                    font-weight: 500;
                    word-break: break-all;
                }
                .file-item .file-size {
                    font-size: 12px;
                    color: #6B7280;
                    white-space: nowrap;
                }
                .file-item select {
                    padding: 6px 10px;
                    border: 1px solid #D1D5DB;
                    border-radius: 8px;
                    font-size: 13px;
                }
                .file-item .remove-btn {
                    background: #FEE2E2;
                    color: #DC2626;
                    border: none;
                    padding: 6px 10px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: all 0.2s;
                }
                .file-item .remove-btn:hover {
                    background: #DC2626;
                    color: white;
                }
                .file-item .file-icon {
                    font-size: 24px;
                }
            </style>

            <script>
            let uploadedFiles = [];

            function getFileIcon(type) {
                if (type.includes('pdf')) return '📕';
                if (type.includes('image')) return '🖼️';
                if (type.includes('word') || type.includes('doc')) return '📝';
                return '📄';
            }

            function formatSize(bytes) {
                if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' МБ';
                if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' КБ';
                return bytes + ' Б';
            }

            function handleFiles(files) {
                if (uploadedFiles.length + files.length > 10) {
                    alert('Максимум 10 файлов!');
                    return;
                }
                
                for (let file of files) {
                    if (file.size > 10485760) {
                        alert('Файл ' + file.name + ' слишком большой (макс. 10 МБ)');
                        continue;
                    }
                    
                    uploadedFiles.push(file);
                    renderFiles();
                }
                
                document.getElementById('fileInput').value = '';
            }

            function removeFile(index) {
                uploadedFiles.splice(index, 1);
                renderFiles();
            }

            function renderFiles() {
                const container = document.getElementById('uploadedFiles');
                const typesContainer = document.getElementById('documentTypesContainer');
                
                container.innerHTML = '';
                typesContainer.innerHTML = '';
                
                uploadedFiles.forEach((file, index) => {
                    // Визуальный элемент
                    const div = document.createElement('div');
                    div.className = 'file-item';
                    div.innerHTML = `
                        <span class="file-icon">${getFileIcon(file.type)}</span>
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">${formatSize(file.size)}</span>
                        <select name="document_types[]" required onchange="this.style.borderColor='#D1D5DB'" style="min-width: 180px;">
                            <option value="">Выберите тип...</option>
                            <option value="charter">Устав компании</option>
                            <option value="inn_certificate">Свидетельство ИНН</option>
                            <option value="ogrn_certificate">Свидетельство ОГРН</option>
                            <option value="director_order">Приказ о назначении</option>
                            <option value="ownership_certificate">Свидетельство собственности</option>
                            <option value="power_of_attorney">Доверенность</option>
                            <option value="other">Прочее</option>
                        </select>
                        <button type="button" class="remove-btn" onclick="removeFile(${index})">✕</button>
                    `;
                    container.appendChild(div);
                    
                    // Скрытый input для файла (нужен для отправки формы)
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'file';
                    hiddenInput.name = 'documents[]';
                    hiddenInput.style.display = 'none';
                    
                    // Копируем файл в скрытый input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    hiddenInput.files = dt.files;
                    
                    typesContainer.appendChild(hiddenInput);
                });
                
                // Показываем/скрываем зону загрузки
                document.getElementById('dropZone').style.display = uploadedFiles.length >= 10 ? 'none' : 'block';
            }

            // Drag & Drop handlers
            function handleDragOver(e) {
                e.preventDefault();
                e.stopPropagation();
                document.getElementById('dropZone').classList.add('drag-over');
            }

            function handleDragLeave(e) {
                e.preventDefault();
                e.stopPropagation();
                document.getElementById('dropZone').classList.remove('drag-over');
            }

            function handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                document.getElementById('dropZone').classList.remove('drag-over');
                
                const files = e.dataTransfer.files;
                handleFiles(files);
            }

            // Предотвращаем стандартное поведение браузера
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                document.body.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            </script>

            <!-- Данные для входа -->
            <div class="section">
                <div class="section-title">🔐 Данные для входа в личный кабинет</div>
                
                <div class="form-group">
                    <label>Email (логин) <span class="required-mark">*</span></label>
                    <input type="email" name="email" 
                           value="{{ old('email') }}" 
                           placeholder="user@company.ru"
                           required>
                    <div class="input-hint">Будет использоваться для входа в систему</div>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Пароль <span class="required-mark">*</span></label>
                        <input type="password" name="password" 
                               placeholder="Минимум 8 символов"
                               required minlength="8" id="password">
                        @error('password') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Подтверждение пароля <span class="required-mark">*</span></label>
                        <input type="password" name="password_confirmation" 
                               placeholder="Повторите пароль"
                               required minlength="8" id="password_confirmation">
                    </div>
                </div>
                <div class="input-hint" id="passwordMatch" style="display:none; color: #DC2626;">
                    ❌ Пароли не совпадают
                </div>
            </div>
            
            <!-- 👇 НОВОЕ: Чекбоксы соглашений -->
            <div class="agreement-section">
                <div class="agreement-item">
                    <input type="checkbox" id="agree_personal" name="agree_personal" required>
                    <label for="agree_personal">
                        Я даю <a href="/documents/personal-data-processing.pdf" target="_blank">согласие на обработку персональных данных</a> 
                        и подтверждаю достоверность указанной информации <span class="required-mark">*</span>
                    </label>
                </div>
                
                <div class="agreement-item">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">
                        Я принимаю <a href="/documents/terms-of-service.pdf" target="_blank">условия обслуживания юридических лиц</a> 
                        в АО «Альфа-Банк» <span class="required-mark">*</span>
                    </label>
                </div>
                
                <div class="agreement-item">
                    <input type="checkbox" id="agree_privacy" name="agree_privacy" required>
                    <label for="agree_privacy">
                        Я ознакомлен с <a href="/documents/privacy-policy.pdf" target="_blank">политикой конфиденциальности</a> 
                        и <a href="/documents/tariffs.pdf" target="_blank">тарифами банка</a> <span class="required-mark">*</span>
                    </label>
                </div>
                
                <div class="agreement-item">
                    <input type="checkbox" id="agree_newsletters" name="agree_newsletters">
                    <label for="agree_newsletters">
                        Я согласен получать информационные рассылки о продуктах и услугах банка
                    </label>
                </div>
                
                @error('agree_personal')
                    <div class="error">Необходимо дать согласие на обработку персональных данных</div>
                @enderror
                @error('agree_terms')
                    <div class="error">Необходимо принять условия обслуживания</div>
                @enderror
                @error('agree_privacy')
                    <div class="error">Необходимо ознакомиться с политикой конфиденциальности</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                📤 Отправить заявку на регистрацию
            </button>
        </form>

        <div class="login-link">
            Уже есть аккаунт? <a href="{{ route('login') }}">Войти в систему</a>
        </div>
    </div>

    <script>
        // ========== УПРОЩЁННАЯ СИСТЕМА ЗАГРУЗКИ ФАЙЛОВ ==========
        let uploadedFiles = [];

        function getFileIcon(type) {
            if (type.includes('pdf')) return '📕';
            if (type.includes('image')) return '🖼️';
            if (type.includes('word') || type.includes('doc')) return '📝';
            return '📄';
        }

        function formatSize(bytes) {
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' МБ';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' КБ';
            return bytes + ' Б';
        }

        function renderFiles() {
            const container = document.getElementById('uploadedFiles');
            container.innerHTML = '';
            
            uploadedFiles.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'file-item';
                div.innerHTML = `
                    <span class="file-icon">${getFileIcon(file.type)}</span>
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatSize(file.size)}</span>
                    <select name="document_types[]" class="doc-type-select" data-index="${index}" style="min-width: 180px; padding: 6px 10px; border: 1px solid #D1D5DB; border-radius: 8px;">
                        <option value="">Выберите тип...</option>
                        <option value="charter">Устав компании</option>
                        <option value="inn_certificate">Свидетельство ИНН</option>
                        <option value="ogrn_certificate">Свидетельство ОГРН</option>
                        <option value="director_order">Приказ о назначении</option>
                        <option value="ownership_certificate">Свидетельство собственности</option>
                        <option value="power_of_attorney">Доверенность</option>
                        <option value="other">Прочее</option>
                    </select>
                    <button type="button" class="remove-btn" onclick="removeFile(${index})">✕</button>
                `;
                container.appendChild(div);
            });
            
            document.getElementById('dropZone').style.display = uploadedFiles.length >= 10 ? 'none' : 'block';
            
            // Обновляем скрытый input с файлами
            updateFileInput();
        }

        function updateFileInput() {
            // Создаём DataTransfer с текущими файлами
            const dt = new DataTransfer();
            uploadedFiles.forEach(file => {
                dt.items.add(file);
            });
            
            // Обновляем основной input
            const fileInput = document.getElementById('fileInput');
            fileInput.files = dt.files;
        }

        function handleFiles(files) {
            if (uploadedFiles.length + files.length > 10) {
                alert('Максимум 10 файлов!');
                return;
            }
            
            for (let file of files) {
                if (file.size > 10485760) {
                    alert('Файл ' + file.name + ' слишком большой (макс. 10 МБ)');
                    continue;
                }
                uploadedFiles.push(file);
            }
            
            renderFiles();
        }

        function removeFile(index) {
            uploadedFiles.splice(index, 1);
            renderFiles();
        }

        // Drag & Drop handlers
        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.add('drag-over');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.remove('drag-over');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.remove('drag-over');
            
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        }

        // Предотвращаем стандартное поведение
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            document.body.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // ========== ПРОВЕРКА ПАРОЛЕЙ ==========
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');
        
        function checkPasswords() {
            if (passwordConfirm.value && password.value !== passwordConfirm.value) {
                passwordMatch.style.display = 'block';
                passwordMatch.textContent = '❌ Пароли не совпадают';
                passwordMatch.style.color = '#DC2626';
            } else if (passwordConfirm.value && password.value === passwordConfirm.value) {
                passwordMatch.style.display = 'block';
                passwordMatch.style.color = '#16A34A';
                passwordMatch.textContent = '✅ Пароли совпадают';
            } else {
                passwordMatch.style.display = 'none';
            }
        }
        
        password.addEventListener('input', checkPasswords);
        passwordConfirm.addEventListener('input', checkPasswords);
        
        // ========== ПРОВЕРКА ПЕРЕД ОТПРАВКОЙ ==========
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            // Проверка чекбоксов
            const agreePersonal = document.getElementById('agree_personal');
            const agreeTerms = document.getElementById('agree_terms');
            const agreePrivacy = document.getElementById('agree_privacy');
            
            if (!agreePersonal.checked || !agreeTerms.checked || !agreePrivacy.checked) {
                e.preventDefault();
                alert('Пожалуйста, примите все обязательные соглашения перед отправкой заявки.');
                return false;
            }
            
            // Проверка типов документов для загруженных файлов
            const selects = document.querySelectorAll('.doc-type-select');
            let hasEmptyType = false;
            selects.forEach(select => {
                if (!select.value) {
                    hasEmptyType = true;
                    select.style.borderColor = '#DC2626';
                }
            });
            
            if (hasEmptyType && uploadedFiles.length > 0) {
                e.preventDefault();
                alert('Пожалуйста, выберите тип документа для каждого загруженного файла.');
                return false;
            }
            
            console.log('Отправка формы с файлами:', uploadedFiles.length);
        });
    </script>

    <!-- 👇 СКРИПТ ДЛЯ ПРОВЕРКИ ПАРОЛЕЙ -->
    <script>
        // Проверка совпадения паролей
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');
        
        function checkPasswords() {
            if (passwordConfirm.value && password.value !== passwordConfirm.value) {
                passwordMatch.style.display = 'block';
                passwordMatch.textContent = '❌ Пароли не совпадают';
            } else if (passwordConfirm.value && password.value === passwordConfirm.value) {
                passwordMatch.style.display = 'block';
                passwordMatch.style.color = '#16A34A';
                passwordMatch.textContent = '✅ Пароли совпадают';
            } else {
                passwordMatch.style.display = 'none';
            }
        }
        
        password.addEventListener('input', checkPasswords);
        passwordConfirm.addEventListener('input', checkPasswords);
        
        // Проверка чекбоксов перед отправкой
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const agreePersonal = document.getElementById('agree_personal');
            const agreeTerms = document.getElementById('agree_terms');
            const agreePrivacy = document.getElementById('agree_privacy');
            
            if (!agreePersonal.checked || !agreeTerms.checked || !agreePrivacy.checked) {
                e.preventDefault();
                alert('Пожалуйста, примите все обязательные соглашения перед отправкой заявки.');
            }
        });


    </script>
    
</body>
</html>