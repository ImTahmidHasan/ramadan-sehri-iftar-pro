document.addEventListener('DOMContentLoaded', function() {
    function updateRamadanTimers() {
        const now = new Date();
        const widgets = document.querySelectorAll('.rsip-widget-instance');

        widgets.forEach(widget => {
            const timerDisplay = widget.querySelector('.rsip-timer-display');
            const statusLabel = widget.querySelector('.rsip-status-label');
            
            if (!timerDisplay) return;

            const sehriStr = timerDisplay.dataset.sehri; // Expected "05:04"
            const iftarStr = timerDisplay.dataset.iftar; // Expected "18:05"

            const getTargetDate = (timeStr, isIftar = false) => {
                const d = new Date();
                let [hours, minutes] = timeStr.split(':').map(Number);

                // AUTO-FIX: If Iftar is "06:15", convert to 18:15
                if (isIftar && hours < 12) {
                    hours += 12;
                }

                d.setHours(hours, minutes, 0, 0);
                return d;
            };

            const targetSehri = getTargetDate(sehriStr, false);
            const targetIftar = getTargetDate(iftarStr, true);
            
            let target, labelText;

            // 1. Before Sehri
            if (now < targetSehri) {
                target = targetSehri;
                labelText = "Time left for Sehri";
            } 
            // 2. Between Sehri and Iftar (This is where you are now at 11:05 AM)
            else if (now >= targetSehri && now < targetIftar) {
                target = targetIftar;
                labelText = "Time left for Iftar";
            } 
            // 3. After Iftar
            else {
                target = new Date(targetSehri.getTime() + 86400000);
                labelText = "Next day Sehri countdown";
            }

            const diff = target - now;
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            if (statusLabel) statusLabel.innerText = labelText;
            
            // Format time as 00:00:00
            const displayH = h.toString().padStart(2, '0');
            const displayM = m.toString().padStart(2, '0');
            const displayS = s.toString().padStart(2, '0');
            
            timerDisplay.innerText = `${displayH}:${displayM}:${displayS}`;
        });
    }

    setInterval(updateRamadanTimers, 1000);
    updateRamadanTimers();
});