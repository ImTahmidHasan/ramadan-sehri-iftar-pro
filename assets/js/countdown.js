document.addEventListener('DOMContentLoaded', function() {
    const timer = document.getElementById('rsip-timer');
    const label = document.getElementById('rsip-label');
    if (!timer) return;

    function update() {
        const now = new Date();
        const sehriTime = timer.dataset.sehri;
        const iftarTime = timer.dataset.iftar;

        const parseTime = (tStr) => {
            const [h, m] = tStr.split(':');
            const d = new Date();
            d.setHours(h, m, 0);
            return d;
        };

        let target = parseTime(sehriTime);
        let status = "Time left for Sehri";

        if (now > target) {
            target = parseTime(iftarTime);
            status = "Time left for Iftar";
        }
        
        if (now > target) {
            target = new Date(parseTime(sehriTime).getTime() + 86400000);
            status = "Next day Sehri countdown";
        }

        const diff = target - now;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        label.innerText = status;
        timer.innerText = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    setInterval(update, 1000);
    update();
});