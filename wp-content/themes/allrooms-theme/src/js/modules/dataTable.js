class DataTable {
	constructor() {
		if (document.querySelector(".tours-content")) {
			// this.toursContent = document.querySelector(".tours-content");
			this.dropdown = document.querySelectorAll(".dropdown-tour");
		}

		if (document.querySelector("table")) {
			this.resizeTimer;
			this.allTables = $("table");
			this.events();
		}
	}

	// EVENTS
	events() {
		$(document).ready(() => {
			this.initiateTable(this.allTables);
			if (this.dropdown) {
				this.dropdown.forEach((button) => {
					this.tourDropdown(button);
				});
			}
		});

		window.addEventListener("resize", () => {
			this.tableResize(this.allTables, 200);
		});
		document.addEventListener("DOMContentLoaded", () => {
			this.tableResize(this.allTables, 200);
		});
	}

	// METHODS
	initiateTable(tables) {
		tables.each(function () {
			if ($(this).data("type") == "upcoming") {
				$(this).dataTable(upcomingShowsConfig);
			}

			if ($(this).data("type") == "berlin") {
				$(this).dataTable(berlinShowsConfig);
			}

			if ($(this).data("type") == "news") {
				$(this).dataTable(basicConfig);
			}

			if (
				$(this).data("type") == "artist" ||
				$(this).data("type") == "tour"
			) {
				$(this).dataTable(extendedConfig);
			}
		});
	}

	tableResize(tables, time) {
		clearTimeout(this.resizeTimer);
		this.resizeTimer = setTimeout(() => {
			tables.each(function () {
				$(this).DataTable().columns.adjust().draw();
			});
		}, time);
	}

	tourDropdown(button) {
		const toggleButton = button.querySelector(
			".dropdown-tour__toggle-button"
		);
		button.addEventListener("click", (e) => {
			if (
				e.target.classList.contains("dropdown-tour__image") ||
				e.target.classList.contains("dropdown-tour__artist-name")
			) {
				e.stopPropagation();
			} else {
				button.parentElement.classList.toggle("hidden");
				if (toggleButton.classList.contains("fa-caret-down")) {
					toggleButton.classList.remove("fa-caret-down");
					toggleButton.classList.add("fa-caret-up");
				} else {
					toggleButton.classList.remove("fa-caret-up");
					toggleButton.classList.add("fa-caret-down");
				}
				this.tableResize(this.allTables, 0);
			}
		});
	}
}

const upcomingShowsConfig = {
	paging: false,
	responsive: true,
	searching: false,
	ordering: false,
	info: false,
	columnDefs: [
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: 1 },
		{ responsivePriority: 3, targets: 2 },
		{ responsivePriority: 6, targets: 3 },
		{ responsivePriority: 5, targets: 4 },
		{ responsivePriority: 4, targets: 5 },
	],
	language: {
		zeroRecords: "No shows found.",
	},
};

const berlinShowsConfig = {
	paging: false,
	responsive: true,
	searching: false,
	ordering: false,
	info: false,
	columnDefs: [
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: 1 },
		{ responsivePriority: 6, targets: 2 },
		{ responsivePriority: 3, targets: 3 },
		{ responsivePriority: 5, targets: 4 },
		{ responsivePriority: 4, targets: 5 },
	],
	language: {
		zeroRecords: "No shows found.",
	},
};

const basicConfig = {
	paging: false,
	responsive: true,
	searching: false,
	ordering: false,
	info: false,
	columnDefs: [
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: 1 },
		{ responsivePriority: 3, targets: 3 },
		{ responsivePriority: 4, targets: 2 },
	],
	language: {
		zeroRecords: "No shows found.",
	},
};

const extendedConfig = {
	paging: false,
	responsive: true,
	searching: false,
	ordering: false,
	info: false,
	columnDefs: [
		{ responsivePriority: 1, targets: 0 },
		{ responsivePriority: 2, targets: 1 },
		{ responsivePriority: 3, targets: 2 },
		{ responsivePriority: 5, targets: 3 },
		{ responsivePriority: 4, targets: 4, width: 100 },
	],
	language: {
		zeroRecords: "No shows found.",
	},
};

export default DataTable;
