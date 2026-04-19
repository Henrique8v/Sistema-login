function cleanANSI(text) {
    if (!text || text.includes("<!DOCTYPE")) return null;
    return text.replace(/[\u001b\u009b][[()#;?]*(?:[0-9]{1,4}(?:;[0-9]{0,4})*)?[0-9A-ORZcf-nqry=><]/g, '')
               .replace(/[^\x20-\x7E\xA0-\xFF\n\r\t]/g, ''); 
}

function systemAction(type) {
    fetch(`pinggy_panel.php?action=${type}&nocache=${Date.now()}`).then(() => {
        setTimeout(refreshSystem, 600);
    });
}

function updateToggle(idForm, idBtnStop, isOnline, labelId) {
    const form = document.getElementById(idForm);
    const stop = document.getElementById(idBtnStop);
    const label = document.getElementById(labelId);
    
    if (isOnline) {
        if(form) form.style.display = 'none';
        if(stop) stop.style.display = 'block';
        if(label) { label.innerText = "ONLINE"; label.style.color = "#0F0"; }
    } else {
        if(form) form.style.display = 'block';
        if(stop) stop.style.display = 'none';
        if(label) { label.innerText = "OFFLINE"; label.style.color = "#F91313"; }
    }
}

function refreshSystem() {
    const cacheBuster = "?t=" + new Date().getTime();
    
    // Status Túnel
    fetch('status.txt' + cacheBuster).then(r => r.text()).then(text => {
        const online = text.trim() === "ONLINE";
        updateToggle('form-pinggy', 'btn-off-pinggy', online, 'status-pinggy-label');
        const gs = document.getElementById('status-text-global');
        if(gs) {
            gs.innerText = online ? "ONLINE" : "OFFLINE";
            gs.style.color = online ? "#0F0" : "#F91313";
        }
    }).catch(() => {});

    // Status Rastros
    fetch('status_rastros.txt' + cacheBuster).then(r => r.text()).then(text => {
        const online = text.trim() === "ONLINE";
        updateToggle('form-rastros', 'btn-off-rastros', online, 'status-rastros-label');
    }).catch(() => {});

    // Links config.json
    fetch('config.json' + cacheBuster).then(r => r.json()).then(data => {
        document.getElementById('link-main').innerText = data.link_login;
        document.getElementById('link-main').href = data.link_login;
        document.getElementById('link-perfil').innerText = data.link_perfil;
        document.getElementById('link-perfil').href = data.link_perfil;
    }).catch(() => {});

    // Logs
    const logMap = {'log-rastros': 'logs/rastros_live.log', 'log-pinggy': 'logs/pinggy_live.log'};
    for (let id in logMap) {
        fetch(logMap[id] + cacheBuster).then(r => r.text()).then(d => {
            let clean = cleanANSI(d);
            if (clean) {
                const el = document.getElementById(id);
                if(el) {
                    el.innerText = clean;
                    el.scrollTop = el.scrollHeight;
                }
            }
        }).catch(() => {});
    }
}

setInterval(refreshSystem, 2500);