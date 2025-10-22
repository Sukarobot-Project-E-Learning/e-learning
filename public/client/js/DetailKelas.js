const tools = [
    { name: "VS Code", icon: "https://cdn-icons-png.flaticon.com/512/906/906324.png" },
    { name: "Adobe Illustrator", icon: "https://cdn-icons-png.flaticon.com/512/5968/5968520.png" },
    { name: "Google Ads", icon: "https://cdn-icons-png.flaticon.com/512/888/888859.png" },
    { name: "PowerPoint", icon: "https://cdn-icons-png.flaticon.com/512/732/732190.png" },
    { name: "Canva", icon: "https://cdn-icons-png.flaticon.com/512/5968/5968705.png" }
  ];

  const toolsList = document.getElementById("toolsList");
  tools.forEach((tool, i) => {
    const div = document.createElement("div");
    div.className = "flex items-center gap-3 tool-hidden";
    div.innerHTML = `<img src="${tool.icon}" class="w-8 h-8"><span class="text-sm font-medium">${tool.name}</span>`;
    toolsList.appendChild(div);
    setTimeout(() => div.classList.add("tool-show"), i * 400);
  });

  document.querySelectorAll(".accordion").forEach(acc => {
    acc.addEventListener("click", () => {
      acc.classList.toggle("open");
      let toggle = acc.querySelector(".toggle");
      toggle.textContent = acc.classList.contains("open") ? "−" : "+";
    });
  });
