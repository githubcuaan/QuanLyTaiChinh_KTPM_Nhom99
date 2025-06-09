// Hàm format số tiền
function formatMoney(amount) {
    return amount.toLocaleString('vi-VN') + ' đ';
}

// Hàm lấy giá trị ngày từ input
function getDateRange() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    return { startDate, endDate };
}

// Hàm hiển thị modal chọn khoảng thời gian
function showDateRangeModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Chọn khoảng thời gian</h2>
            <div class="date-range-inputs">
                <div>
                    <label for="start-date">Từ ngày:</label>
                    <input type="date" id="start-date" required>
                </div>
                <div>
                    <label for="end-date">Đến ngày:</label>
                    <input type="date" id="end-date" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').remove()">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="applyDateRange()">Áp dụng</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Hiển thị modal
    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('show'), 10);

    // Xử lý đóng modal
    const closeBtn = modal.querySelector('.close');
    closeBtn.onclick = function() {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    };

    // Set default values
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    modal.querySelector('#start-date').value = firstDay.toISOString().split('T')[0];
    modal.querySelector('#end-date').value = lastDay.toISOString().split('T')[0];
}

// Hàm áp dụng khoảng thời gian đã chọn
function applyDateRange() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (!startDate || !endDate) {
        alert('Vui lòng chọn đầy đủ ngày bắt đầu và kết thúc');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc');
        return;
    }

    // Đóng modal
    const modal = document.querySelector('.modal');
    modal.classList.remove('show');
    setTimeout(() => modal.remove(), 300);

    // Cập nhật dữ liệu
    updateDashboardData();
}

// Hàm cập nhật dữ liệu cho biểu đồ
async function updateDashboardData() {
    try {
        const { startDate, endDate } = getDateRange();
        const queryParams = new URLSearchParams();
        if (startDate) queryParams.append('start_date', startDate);
        if (endDate) queryParams.append('end_date', endDate);

        const response = await fetch(`../api/get_dashboard_data.php?${queryParams.toString()}`);
        const data = await response.json();

        if (data.error) {
            console.error('Error:', data.error);
            return;
        }

        // Cập nhật tổng số dư
        document.querySelector('.total .card:nth-child(1) h2').textContent = formatMoney(data.total_balance);
        
        // Cập nhật tổng thu nhập
        document.querySelector('.total .card:nth-child(2) h2').textContent = formatMoney(data.total_income);
        
        // Cập nhật tổng chi tiêu
        document.querySelector('.total .card:nth-child(3) h2').textContent = formatMoney(data.total_expense);

        // Cập nhật dữ liệu cho biểu đồ tròn
        const jarData = {
            labels: data.jar_balances.map(jar => jar.name),
            data: data.jar_balances.map(jar => jar.percentage || 0),
            amounts: data.jar_balances.map(jar => jar.balance || 0),
            colors: [
                '#FF6384', // Thiết Yếu - Hồng
                '#36A2EB', // Tự Do Tài Chính - Xanh dương
                '#FFCE56', // Giáo Dục - Vàng
                '#4BC0C0', // Hưởng Thụ - Xanh ngọc
                '#9966FF', // Thiện Tâm - Tím
                '#FF9F40'  // Tiết Kiệm - Cam
            ]
        };

        // Cập nhật biểu đồ tròn
        pieChart.data.labels = jarData.labels;
        pieChart.data.datasets[0].data = jarData.data;
        pieChart.data.datasets[0].backgroundColor = jarData.colors;
        pieChart.data.datasets[0].hoverBackgroundColor = jarData.colors.map(color => Chart.helpers.color(color).alpha(0.8).rgbString());
        pieChart.options.plugins.tooltip.callbacks.label = function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const amount = jarData.amounts[context.dataIndex] || 0;
            return [
                `${label}: ${value}%`,
                `Số dư: ${formatMoney(amount)}`
            ];
        };
        pieChart.update();

        // Cập nhật biểu đồ cột
        barChart.data.datasets[0].data = [data.total_income, data.total_expense];
        barChart.update();

        // Cập nhật thông tin 6 hũ
        const jarElements = document.querySelectorAll('.jar');
        data.jar_balances.forEach((jar, index) => {
            if (jarElements[index]) {
                jarElements[index].querySelector('strong').textContent = formatMoney(jar.balance || 0);
                jarElements[index].querySelector('span').textContent = (jar.percentage || 0) + '%';
                
                // Thêm data attributes cho mỗi hũ
                jarElements[index].setAttribute('data-jar-id', index + 1);
                jarElements[index].setAttribute('data-jar-name', jar.name);
                jarElements[index].setAttribute('data-jar-balance', jar.balance || 0);
                
                // Thêm sự kiện click cho mỗi hũ
                jarElements[index].addEventListener('click', function() {
                    const jarId = this.getAttribute('data-jar-id');
                    const jarName = this.getAttribute('data-jar-name');
                    const jarBalance = this.getAttribute('data-jar-balance');
                    openExpenseModalFromJar(jarId, jarName, jarBalance);
                });
            }
        });

    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
}

// Hàm mở modal thêm chi tiêu từ hũ
function openExpenseModalFromJar(jarId, jarName, jarBalance) {
    const expenseModal = document.getElementById('expense-form-container');
    const expenseJarSelect = document.getElementById('expense-jar-select');
    const expenseJarBalance = document.getElementById('expense-jar-balance');
    
    // Set giá trị cho form
    document.getElementById('expense-date').valueAsDate = new Date();
    expenseJarSelect.value = jarId;
    expenseJarBalance.value = formatMoney(jarBalance);
    
    // Hiển thị modal
    expenseModal.style.display = 'block';
    setTimeout(() => {
        expenseModal.classList.add('show');
    }, 10);
}

// Dữ liệu mẫu cho biểu đồ
const jarData = {
    labels: ['Thiết Yếu', 'Tự Do Tài Chính', 'Giáo Dục', 'Hưởng Thụ', 'Thiện Tâm', 'Tiết Kiệm'],
    data: [8, 10, 5, 10, 10, 55], // Phần trăm từ cấu hình
    amounts: [0, 0, 0, 0, 0, 0], // Số tiền thực tế trong mỗi hũ
    colors: [
        '#FF6384', // Thiết Yếu - Hồng
        '#36A2EB', // Tự Do Tài Chính - Xanh dương
        '#FFCE56', // Giáo Dục - Vàng
        '#4BC0C0', // Hưởng Thụ - Xanh ngọc
        '#9966FF', // Thiện Tâm - Tím
        '#FF9F40'  // Tiết Kiệm - Cam
    ]
};

// Tạo biểu đồ tròn
const ctx = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: jarData.labels,
        datasets: [{
            data: jarData.data,
            backgroundColor: jarData.colors,
            hoverBackgroundColor: jarData.colors.map(color => Chart.helpers.color(color).alpha(0.8).rgbString()),
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 15,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                top: 20,
                bottom: 20
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Phân bố hũ',
                font: {
                    size: 16,
                    weight: 'bold'
                },
                padding: {
                    top: 10,
                    bottom: 20
                }
            },
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 12
                    },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const amount = jarData.amounts[context.dataIndex] || 0;
                        return [
                            `${label}: ${value}%`,
                            `Số dư: ${formatMoney(amount)}`
                        ];
                    }
                }
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
}); 

// Tạo biểu đồ cột 
const barCtx = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(barCtx, 
    {
        type: 'bar',
        data: {
            labels: ['Thu nhập', 'Chi tiêu'],
            datasets: [{
                data: [0,0],
                backgroundColor: [
                    '#36A2EB', // Màu xanh dương cho thu nhập
                    '#FF6384'  // Màu hồng cho chi tiêu
                ],
                borderWidth: 2,
                borderColor: '#fff',
                borderRadius: 5,
                barThickness: 40  
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 20,
                    bottom: 20
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Thu nhập và Chi tiêu',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw || 0;
                            return formatMoney(value);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatMoney(value);
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    }); 

// Hàm xử lý filter buttons
function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter button');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Set date range based on button
            const today = new Date();
            let startDate, endDate;
            
            switch(this.textContent) {
                case 'Tháng này':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                case 'Quý này':
                    const quarter = Math.floor(today.getMonth() / 3);
                    startDate = new Date(today.getFullYear(), quarter * 3, 1);
                    endDate = new Date(today.getFullYear(), (quarter + 1) * 3, 0);
                    break;
                case 'Năm nay':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = new Date(today.getFullYear(), 11, 31);
                    break;
                case 'Tùy chỉnh':
                    showDateRangeModal();
                    return;
            }
            
            if (startDate && endDate) {
                document.getElementById('start-date').value = startDate.toISOString().split('T')[0];
                document.getElementById('end-date').value = endDate.toISOString().split('T')[0];
                updateDashboardData();
            }
        });
    });
}

// Cập nhật dữ liệu khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    // Set default date range to current month
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    // Create hidden date inputs if they don't exist
    if (!document.getElementById('start-date')) {
        const startDateInput = document.createElement('input');
        startDateInput.type = 'date';
        startDateInput.id = 'start-date';
        startDateInput.style.display = 'none';
        document.body.appendChild(startDateInput);
    }
    
    if (!document.getElementById('end-date')) {
        const endDateInput = document.createElement('input');
        endDateInput.type = 'date';
        endDateInput.id = 'end-date';
        endDateInput.style.display = 'none';
        document.body.appendChild(endDateInput);
    }
    
    document.getElementById('start-date').value = firstDay.toISOString().split('T')[0];
    document.getElementById('end-date').value = lastDay.toISOString().split('T')[0];
    
    // Setup filter buttons
    setupFilterButtons();
    
    // Initial data load
    updateDashboardData();
});

// Cập nhật dữ liệu mỗi 30 giây
setInterval(updateDashboardData, 30000); 