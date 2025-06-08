// Hàm format số tiền
function formatMoney(amount) {
    return amount.toLocaleString('vi-VN') + ' đ';
}

// Hàm cập nhật dữ liệu cho biểu đồ
async function updateDashboardData() {
    try {
        const response = await fetch('../api/get_dashboard_data.php');
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
        pieChart.data.datasets[0].data = jarData.data;
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
                        const amount = jarData.amounts[context.dataIndex];
                        return [
                            `${label}: ${value}%`,
                            `Số tiền: ${formatMoney(amount)}`
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

// Cập nhật dữ liệu khi trang được tải
document.addEventListener('DOMContentLoaded', updateDashboardData);

// Cập nhật dữ liệu mỗi 30 giây
setInterval(updateDashboardData, 30000); 