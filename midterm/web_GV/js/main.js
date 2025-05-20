// main.js - Xử lý các chức năng tương tác trên trang

document.addEventListener('DOMContentLoaded', function() {
    // Hiển thị thông báo
    const messages = document.querySelectorAll('.success-message, .error-message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    // Xác nhận trước khi xóa
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (deleteButtons) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc chắn muốn xóa tài liệu này không?')) {
                    e.preventDefault();
                }
            });
        });
    }
    
    // Hiển thị tên file khi chọn
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            const fileSize = (e.target.files[0].size / 1024).toFixed(2) + ' KB';
            
            const fileInfoElement = document.getElementById('file-info');
            if (!fileInfoElement) {
                const infoElement = document.createElement('div');
                infoElement.id = 'file-info';
                infoElement.innerHTML = `<p>File đã chọn: <strong>${fileName}</strong> (${fileSize})</p>`;
                this.parentNode.appendChild(infoElement);
            } else {
                fileInfoElement.innerHTML = `<p>File đã chọn: <strong>${fileName}</strong> (${fileSize})</p>`;
            }
        });
    }
    
    // Kích hoạt tìm kiếm khi nhấn Enter
    const searchInput = document.querySelector('.search-bar input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.target.form.submit();
            }
        });
    }
});