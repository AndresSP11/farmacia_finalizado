// calendario.js
document.addEventListener("DOMContentLoaded", () => {
  const fechaInput = document.getElementById("fecha-modificar");
  const calendar = document.getElementById("calendar");
  const monthYear = document.getElementById("month-year");
  const prevMonth = document.getElementById("prev-month");
  const nextMonth = document.getElementById("next-month");
  const calendarGrid = document.querySelector(".calendar-grid");

  let currentDate = new Date();

  function renderCalendar(date) {
    calendarGrid.innerHTML = "";
    const month = date.getMonth();
    const year = date.getFullYear();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const monthName = date.toLocaleString("es-ES", { month: "long" });
    const capitalizedMonth =
      monthName.charAt(0).toUpperCase() + monthName.slice(1); // Capitaliza la primera letra del mes

    monthYear.textContent = capitalizedMonth;

    const daysOfWeek = ["LU", "MA", "MI", "JU", "VI", "SA", "DO"];
    daysOfWeek.forEach((day) => {
      const dayOfWeekDiv = document.createElement("div");
      dayOfWeekDiv.textContent = day;
      dayOfWeekDiv.classList.add("dayOfWeek");
      calendarGrid.appendChild(dayOfWeekDiv);
    });

    for (let i = 0; i < firstDay; i++) {
      calendarGrid.appendChild(document.createElement("div"));
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dayDiv = document.createElement("div");
      dayDiv.textContent = day;
      dayDiv.addEventListener("click", () => {
        fechaInput.value = `${year}-${String(month + 1).padStart(
          2,
          "0"
        )}-${String(day).padStart(2, "0")}`;
        calendar.classList.add("hidden");
      });
      calendarGrid.appendChild(dayDiv);
    }
  }

  fechaInput.addEventListener("click", () => {
    calendar.classList.toggle("hidden");
    renderCalendar(currentDate);
  });

  prevMonth.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  });

  nextMonth.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });

  document.addEventListener("click", (event) => {
    if (!calendar.contains(event.target) && event.target !== fechaInput) {
      calendar.classList.add("hidden");
    }
  });
});
