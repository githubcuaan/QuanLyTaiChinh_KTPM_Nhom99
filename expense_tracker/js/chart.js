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
                            `Số tiền: ${amount.toLocaleString('vi-VN')} đ`
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