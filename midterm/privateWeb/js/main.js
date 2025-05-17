document.addEventListener('DOMContentLoaded', function () {
    // Tab Navigation
    document.querySelectorAll('.nav-link[data-tab]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            const targetId = e.currentTarget.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const offset = 96; // Chiều cao navbar
                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Highlight nav-link on scroll
    window.addEventListener('scroll', () => {
        const sections = document.querySelectorAll('section[id]'); 
        const scrollPos = window.scrollY + 120;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;

            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                document.querySelectorAll('.nav-link[data-tab]').forEach(link => {
                    link.classList.remove('active');
                });

                const activeLink = document.querySelector(`.nav-link[data-tab="${section.id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    });

    // Alert Box
    const alertBox = document.getElementById('alert-box');

    function showAlert(message) {
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
        setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 3000);
    }

    // Show More Publications
    document.getElementById('showMore').addEventListener('click', function () {
        const hiddenCards = document.querySelectorAll('#publication-list .card.d-none');
        for (let i = 0; i < 3 && i < hiddenCards.length; i++) {
            hiddenCards[i].classList.remove('d-none');
        }

        // Nếu không còn card nào ẩn, ẩn nút
        if (document.querySelectorAll('#publication-list .card.d-none').length === 0) {
            this.style.display = 'none';
        }
    });



    // Personal Info
    const savePersonalInfoBtn = document.getElementById('savePersonalInfo');
    savePersonalInfoBtn.addEventListener('click', function () {
        const introduction = document.getElementById('introduction').value;
        const full_name = document.getElementById('full_name').value;
        const position = document.getElementById('position').value;
        const university = document.getElementById('university').value;
        const degree = document.getElementById('degree').value;
        const specialization = document.getElementById('specialization').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const office = document.getElementById('office').value;
        const scholar_link = document.getElementById('scholar').value;
        const research_gate_link = document.getElementById('researchgate').value;

        // AJAX request to save personal info
        fetch('save_personal_info.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `full_name=${encodeURIComponent(full_name)}&position=${encodeURIComponent(position)}&university=${encodeURIComponent(university)}&degree=${encodeURIComponent(degree)}&specialization=${encodeURIComponent(specialization)}&introduction=${encodeURIComponent(introduction)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&office=${encodeURIComponent(office)}&scholar=${encodeURIComponent(scholar_link)}&researchgate=${encodeURIComponent(research_gate_link)}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Thông tin cá nhân đã được cập nhật thành công!');
                } else {
                    showAlert('Có lỗi xảy ra khi cập nhật thông tin cá nhân.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi cập nhật thông tin cá nhân.');
            });
    });

    // Publications
    const publicationList = document.getElementById('publication-list');
    const addPublicationBtn = document.getElementById('addPublication');
    const publicationModal = new bootstrap.Modal(document.getElementById('publicationModal'));
    const pubModalTitle = document.getElementById('pubModalTitle');
    const pubTitleInput = document.getElementById('pub-title');
    const pubAuthorsInput = document.getElementById('pub-authors');
    const pubJournalInput = document.getElementById('pub-journal');
    const pubPdfInput = document.getElementById('pub-pdf');
    const savePublicationBtn = document.getElementById('savePublication');

    let editingPublicationId = null; // Track the ID of the publication being edited

    // Function to open the publication modal
    function openPublicationModal(isAdding = true, publicationData = null) {
        if (isAdding) {
            pubModalTitle.textContent = 'Thêm bài báo mới';
            pubTitleInput.value = '';
            pubAuthorsInput.value = '';
            pubJournalInput.value = '';
            pubPdfInput.value = '';
            editingPublicationId = null; // Reset editing ID
        } else {
            pubModalTitle.textContent = 'Sửa bài báo';
            pubTitleInput.value = publicationData.title;
            pubAuthorsInput.value = publicationData.authors;
            pubJournalInput.value = publicationData.journal;
            editingPublicationId = publicationData.id; // Set editing ID
        }
        publicationModal.show();
    }

    // Add Publication Button
    addPublicationBtn.addEventListener('click', function () {
        openPublicationModal();
    });

    // Edit Publication Buttons (Event delegation)
    publicationList.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-pub')) {
            const card = event.target.closest('.card');
            const publicationId = card.getAttribute('data-id');

            fetch(`php/get_publication.php?id=${publicationId}`)
                .then(response => response.json())
                .then(data => {
                    openPublicationModal(false, data);
                })
                .catch(error => {
                    console.error('Error fetching publication:', error);
                    showAlert('Không thể lấy thông tin bài báo.');
                });
        }
    });

    // Delete Publication Buttons (Event delegation)
    publicationList.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-pub')) {
            const card = event.target.closest('.card');
            const publicationId = card.getAttribute('data-id');

            if (confirm('Bạn có chắc chắn muốn xóa bài báo này?')) {
                fetch('php/delete_publication.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${publicationId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            card.remove();
                            showAlert('Bài báo đã được xóa thành công!');
                        } else {
                            showAlert('Có lỗi xảy ra khi xóa bài báo.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi xóa bài báo.');
                    });
            }
        }
    });

    // Save Publication Button
    savePublicationBtn.addEventListener('click', function () {
        const title = pubTitleInput.value;
        const authors = pubAuthorsInput.value;
        const journal = pubJournalInput.value;
        const pdfFile = pubPdfInput.files[0];

        const formData = new FormData();
        formData.append('title', title);
        formData.append('authors', authors);
        formData.append('journal', journal);
        formData.append('pdf', pdfFile);
        if (editingPublicationId) {
            formData.append('id', editingPublicationId);
        }

        fetch(editingPublicationId ? 'php/update_publication.php' : 'php/save_publication.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    publicationModal.hide();
                    showAlert('Bài báo đã được lưu thành công!');
                    location.reload();
                } else {
                    showAlert('Có lỗi xảy ra khi lưu bài báo.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi lưu bài báo.');
            });
    });

    // Show More Publications
    document.getElementById('showMore').addEventListener('click', function () {
        const hiddenCards = document.querySelectorAll('#publication-list .card.d-none');
        for (let i = 0; i < 3 && i < hiddenCards.length; i++) {
            hiddenCards[i].classList.remove('d-none');
        }

        // Nếu không còn card nào ẩn, ẩn nút
        if (document.querySelectorAll('#publication-list .card.d-none').length === 0) {
            this.style.display = 'none';
        }
    });

    // Projects
    const projectList = document.getElementById('project-list');
    const addProjectBtn = document.getElementById('addProject');
    const projectModal = new bootstrap.Modal(document.getElementById('projectModal'));
    const projModalTitle = document.getElementById('projModalTitle');
    const projTitleInput = document.getElementById('proj-title');
    const projDescInput = document.getElementById('proj-desc');
    const projFileInput = document.getElementById('proj-file');
    const projLinkTextInput = document.getElementById('proj-link-text');
    const saveProjectBtn = document.getElementById('saveProject');
    
    let editingProjectId = null; // Track the ID of the project being edited




    // Function to open the project modal
    function openProjectModal(isAdding = true, projectData = null) {
        if (isAdding) {
            projModalTitle.textContent = 'Thêm dự án mới';
            projTitleInput.value = '';
            projDescInput.value = '';
            projFileInput.value = '';
            projLinkTextInput.value = '';
            editingProjectId = null; // Reset editing ID
        } else {
            projModalTitle.textContent = 'Sửa dự án';
            projTitleInput.value = projectData.title;
            projDescInput.value = projectData.description;
            projLinkTextInput.value = projectData.button_text;
            editingProjectId = projectData.id; // Set editing ID

            const existingFileDiv = document.getElementById('existing-file'); // <-- cần lấy DOM element này trước

            if (projectData.link) {
                existingFileDiv.innerHTML = `
                    <p>File hiện tại: <a href="${projectData.link}" target="_blank">${getFileName(projectData.link)}</a></p>`;
            } else {
                existingFileDiv.innerHTML = '<p>Chưa có file đính kèm.</p>';
            }
            function getFileName(path) {
                return path.split('/').pop();
            }
        }
        projectModal.show();
    }

    // Add Project Button
    addProjectBtn.addEventListener('click', function () {
        openProjectModal();
    });

    // Edit Project Buttons (Event delegation)
    projectList.addEventListener('click', function (event) {
        if (event.target.classList.contains('edit-proj')) {
            const card = event.target.closest('.card');
            if (!card) return;

            const projectId = card.getAttribute('data-id');
            editingProjectId = projectId;

            fetch(`get_project.php?id=${projectId}`)
                .then(response => response.json())
                .then(data => {
                    openProjectModal(false, data); // Hiển thị modal với dữ liệu lấy được
                })
                .catch(error => {
                    console.error('Lỗi lấy dữ liệu:', error);
                    alert('Không thể lấy thông tin dự án.');
                });
        }
    });

    // Delete Project Buttons (Event delegation)
    projectList.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-proj')) {
            const card = event.target.closest('.card');
            const projectId = card.getAttribute('data-id');

            if (confirm('Bạn có chắc chắn muốn xóa dự án này?')) {
                fetch('delete_project.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${projectId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            card.remove();
                            showAlert('Dự án đã được xóa thành công!');
                        } else {
                            showAlert('Có lỗi xảy ra khi xóa dự án.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Có lỗi xảy ra khi xóa dự án.');
                    });
            }
        }
    });

    // Save Project Button
    saveProjectBtn.addEventListener('click', function () {
        
        const title = projTitleInput.value.trim();
        const description = projDescInput.value.trim();
        const file = projFileInput.files[0];
        const button_text = projLinkTextInput.value.trim();
        
        if (!title || !description || !button_text || (!file && !editingProjectId)) {
            showAlert('Vui lòng điền đầy đủ thông tin dự án.');
            return;
        }
        
        console.log('lồn'); // phải in ra được nút
        
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('button_text', button_text);
        if (file) {
            formData.append('file', file);
        }

        if (editingProjectId) {
            formData.append('id', editingProjectId);
        }

        fetch(editingProjectId ? 'update_project.php' : 'save_project.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                projectModal.hide();
                showAlert('Dự án đã được lưu thành công!');
                location.reload();
            } else {
                showAlert(data.message || 'Có lỗi xảy ra khi lưu dự án.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi lưu dự án.');
        });
    });


    // Show More Projects
    document.getElementById('showMoreProjects').addEventListener('click', function() {
        const hiddenCards = document.querySelectorAll('#project-list .card.d-none');
        for (let i = 0; i < 3 && i < hiddenCards.length; i++) {
            hiddenCards[i].classList.remove('d-none');
        }
        hiddenCards
        // Nếu không còn card nào ẩn, ẩn nút
        if (document.querySelectorAll('#project-list .card.d-none').length === 0) {
            this.style.display = 'none';
        }
    });


    // Schedule
    const saveScheduleBtn = document.getElementById('saveSchedule');
    saveScheduleBtn.addEventListener('click', function () {
        const scheduleData = [];
        const scheduleRows = document.querySelectorAll('.schedule-table tbody tr');

        scheduleRows.forEach(row => {
            const time = row.querySelector('td:first-child').textContent.trim();
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            const rowData = { time: time };

            days.forEach((day, index) => {
                const select = row.querySelector(`td:nth-child(${index + 2}) select.schedule-type`);
                const details = row.querySelector(`td:nth-child(${index + 2}) input.schedule-details`);

                rowData[day] = {
                    type: select.value,
                    details: details.value,
                };
            });

            scheduleData.push(rowData);
        });

        // AJAX request to save the schedule
        fetch('php/save_schedule.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(scheduleData),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Lịch đã được lưu thành công!');
                } else {
                    showAlert('Có lỗi xảy ra khi lưu lịch.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi lưu lịch.');
            });
    });

    // Account Settings
    const saveAccountBtn = document.getElementById('saveAccount');
    saveAccountBtn.addEventListener('click', function () {
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const showEmail = document.getElementById('show-email').checked;
        const showPhone = document.getElementById('show-phone').checked;
        const showSchedule = document.getElementById('show-schedule').checked;

        // Validate new password and confirm password
        if (newPassword !== confirmPassword) {
            showAlert('Mật khẩu mới và xác nhận mật khẩu không khớp.');
            return;
        }

        // AJAX request to save account settings
        fetch('php/save_account_settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `currentPassword=${encodeURIComponent(currentPassword)}&newPassword=${encodeURIComponent(newPassword)}&showEmail=${showEmail}&showPhone=${showPhone}&showSchedule=${showSchedule}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Cài đặt tài khoản đã được lưu thành công!');
                } else {
                    showAlert('Có lỗi xảy ra khi lưu cài đặt tài khoản.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi lưu cài đặt tài khoản.');
            });
    });
});

