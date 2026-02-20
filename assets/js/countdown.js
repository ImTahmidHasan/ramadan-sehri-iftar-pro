document.addEventListener('DOMContentLoaded', function() {
    function updateRamadanTimers() {
        const now = new Date();
        const widgets = document.querySelectorAll('.rsip-widget-instance');

        widgets.forEach(widget => {
            const timerDisplay = widget.querySelector('.rsip-timer-display');
            const statusLabel = widget.querySelector('.rsip-status-label');
            
            if (!timerDisplay) return;

            const sehriStr = timerDisplay.dataset.sehri; 
            const iftarStr = timerDisplay.dataset.iftar; 

            const getTargetDate = (timeStr, isIftar = false) => {
                const d = new Date();
                let [hours, minutes] = timeStr.split(':').map(Number);

                // FIX: If Iftar is "06:15", it must be 18:15 (PM)
                if (isIftar && hours < 12) {
                    hours += 12;
                }
                // FIX: If Sehri is "05:00", ensure it's AM
                if (!isIftar && hours > 12) {
                    hours -= 12; 
                }

                d.setHours(hours, minutes, 0, 0);
                return d;
            };

            const targetSehri = getTargetDate(sehriStr, false);
            const targetIftar = getTargetDate(iftarStr, true);
            
            let target, labelText;

            if (now < targetSehri) {
                target = targetSehri;
                labelText = "Time left for Sehri";
            } 
            else if (now >= targetSehri && now < targetIftar) {
                target = targetIftar;
                labelText = "Time left for Iftar";
            } 
            else {
                // After Iftar, target tomorrow's Sehri
                target = new Date(targetSehri.getTime() + 86400000);
                labelText = "Next day Sehri countdown";
            }

            const diff = target - now;
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            if (statusLabel) statusLabel.innerText = labelText;
            
            timerDisplay.innerText = 
                `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        });
    }

    setInterval(updateRamadanTimers, 1000);
    updateRamadanTimers();
});