document.addEventListener('DOMContentLoaded', function() {
    const timerDisplay = document.getElementById('rsip-timer');
    const statusText = document.getElementById('rsip-status-text');
    if (!timerDisplay) return;

    function updateCountdown() {
        const now = new Date();
        const fajrStr = timerDisplay.dataset.fajr;
        const maghribStr = timerDisplay.dataset.maghrib;

        const getTimestamp = (timeStr) => {
            const [hours, minutes] = timeStr.split(':');
            const d = new Date();
            d.setHours(parseInt(hours), parseInt(minutes), 0);
            return d;
        };

        let fajr = getTimestamp(fajrStr);
        let maghrib = getTimestamp(maghribStr);
        let target, label;

        if (now < fajr) {
            target = fajr;
            label = "Time left for Sehri";
        } else if (now >= fajr && now < maghrib) {
            target = maghrib;
            label = "Time left for Iftar";
        } else {
            // After Iftar, target next day Sehri
            target = new Date(fajr.getTime() + 24 * 60 * 60 * 1000);
            label = "Next Day Sehri Countdown";
        }

        const diff = target - now;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        statusText.innerText = label;
        timerDisplay.innerText = 
            `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
});