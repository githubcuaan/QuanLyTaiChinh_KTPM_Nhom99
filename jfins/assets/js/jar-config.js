document.addEventListener('DOMContentLoaded', function() {
    const jarInputs = document.querySelectorAll('.jar-percent');
    const totalPercentElement = document.querySelector('.total-percent');
    const saveConfigBtn = document.querySelector('.save-config-btn');
    let jarAllocations = [];

    // Function to update total percentage display
    function updateTotalPercent() {
        let total = 0;
        jarInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        totalPercentElement.textContent = `Tổng: ${total}%`;
        
        // Enable/disable save button based on total
        saveConfigBtn.disabled = Math.abs(total - 100) > 0.01;
    }

    // Add input event listeners to all jar percentage inputs
    jarInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            // Ensure value is between 0 and 100
            let value = parseFloat(this.value) || 0;
            value = Math.max(0, Math.min(100, value));
            this.value = value;
            
            updateTotalPercent();
        });
    });

    // Save configuration
    saveConfigBtn.addEventListener('click', async function() {
        if (this.disabled) {
            alert('Tổng phần trăm phải bằng 100%');
            return;
        }

        // Collect all jar allocations
        jarAllocations = Array.from(jarInputs).map((input, index) => ({
            jar_id: index + 1,
            percentage: parseFloat(input.value) || 0
        }));

        try {
            const response = await fetch('../api/jar/update_allocations.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ allocations: jarAllocations })
            });

            const result = await response.json();
            
            if (result.success) {
                alert('Cập nhật thành công!');
                // Refresh the page to show updated balances
                window.location.reload();
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật cấu hình');
        }
    });

    // Load initial jar allocations from database
    async function loadJarAllocations() {
        try {
            const response = await fetch('../api/jar/get_allocations.php');
            const data = await response.json();
            
            if (data.success) {
                data.allocations.forEach(allocation => {
                    const input = jarInputs[allocation.jar_id - 1];
                    if (input) {
                        input.value = allocation.percentage;
                    }
                });
                updateTotalPercent();
            }
        } catch (error) {
            console.error('Error loading jar allocations:', error);
        }
    }

    // Load initial data
    loadJarAllocations();
}); 