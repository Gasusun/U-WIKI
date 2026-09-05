// ================================
// DỮ LIỆU BẢN ĐỒ
// ================================

const mapData = {

    coso1: {
        name: "Cơ sở 1",

        floors: [
            {
                name: "Tầng trệt",
                file: "images/map/CoSo1/TRET.webp"
            },
            {
                name: "Tầng lửng",
                file: "images/map/CoSo1/LUNG.webp"
            },
            {
                name: "Tầng 1",
                file: "images/map/CoSo1/TANG1.webp"
            },
            {
                name: "Tầng 2",
                file: "images/map/CoSo1/TANG2.webp"
            },
            {
                name: "Tầng 3",
                file: "images/map/CoSo1/TANG3.webp"
            },
            {
                name: "Tầng 4",
                file: "images/map/CoSo1/TANG4.webp"
            },
            {
                name: "Tầng 5",
                file: "images/map/CoSo1/TANG5.webp"
            },
            {
                name: "Tầng 6",
                file: "images/map/CoSo1/TANG6.webp"
            },
            {
                name: "Tầng 7",
                file: "images/map/CoSo1/TANG7.webp"
            },
            {
                name: "Sân thượng",
                file: "images/map/CoSo1/SANTHUONG.webp"
            }
        ]
    },


    // ================================
    // CƠ SỞ 3
    // ================================

    coso3: {
        name: "Cơ sở 3",

        floors: [
            {
                name: "Bản đồ",
                file: "images/map/CoSo3/quan7.webp"
            }
        ]
    },


    // ================================
    // CƠ SỞ 4
    // ================================

    coso4: {
        name: "Cơ sở 4",

        floors: [
            {
                name: "Tầng hầm",
                file: "images/map/CoSo4/TANGHAM.webp"
            },
            {
                name: "Tầng trệt",
                file: "images/map/CoSo4/TANGTRET.webp"
            },
            {
                name: "Tầng lửng",
                file: "images/map/CoSo4/TANGLUNG.webp"
            },
            {
                name: "Tầng 1",
                file: "images/map/CoSo4/TANG1.webp"
            },
            {
                name: "Tầng 2",
                file: "images/map/CoSo4/TANG2.webp"
            },
            {
                name: "Tầng 3",
                file: "images/map/CoSo4/TANG3.webp"
            },
            {
                name: "Tầng 4",
                file: "images/map/CoSo4/TANG4.webp"
            },
            {
                name: "Tầng 5",
                file: "images/map/CoSo4/TANG5.webp"
            },
            {
                name: "Tầng 6",
                file: "images/map/CoSo4/TANG6.webp"
            },
            {
                name: "Tầng 7",
                file: "images/map/CoSo4/TANG7.webp"
            },
            {
                name: "Tầng 8",
                file: "images/map/CoSo4/TANG8.webp"
            },
            {
                name: "Tầng 9",
                file: "images/map/CoSo4/TANG9.webp"
            }
        ]
    }

};


// ================================
// LẤY ELEMENT
// ================================

const branchSelect = document.getElementById("branchSelect");
const branchButton = document.getElementById("branchButton");
const branchDropdown = document.getElementById("branchDropdown");

const floorSelect = document.getElementById("floorSelect");
const floorButton = document.getElementById("floorButton");
const floorDropdown = document.getElementById("floorDropdown");

const selectedBranch = document.getElementById("selectedBranch");
const selectedFloor = document.getElementById("selectedFloor");

const campusMap = document.getElementById("campusMap");

const branchOptions = document.querySelectorAll(".branch-option");


// ================================
// BIẾN CƠ SỞ HIỆN TẠI
// ================================

let currentBranch = "coso1";


// ================================
// HIỂN THỊ DANH SÁCH TẦNG
// ================================

function loadFloors(branchId) {

    const branch = mapData[branchId];

    if (!branch) {
        return;
    }

    // Xóa danh sách cũ
    floorDropdown.innerHTML = "";


    // Tạo danh sách tầng mới
    branch.floors.forEach((floor, index) => {

        const option = document.createElement("div");

        option.classList.add("floor-option");

        if (index === 0) {
            option.classList.add("active");
        }

        option.textContent = floor.name;

        option.dataset.file = floor.file;
        option.dataset.name = floor.name;


        // Khi click tầng
        option.addEventListener("click", function () {

            const file = this.dataset.file;
            const floorName = this.dataset.name;

            campusMap.src = file;

            campusMap.alt =
                `Bản đồ ${branch.name} - ${floorName}`;


            selectedFloor.textContent = floorName;


            // Xóa active cũ
            document
                .querySelectorAll(".floor-option")
                .forEach(item => {
                    item.classList.remove("active");
                });


            // Active tầng hiện tại
            this.classList.add("active");


            // Đóng dropdown
            floorSelect.classList.remove("open");

        });


        floorDropdown.appendChild(option);

    });


    // Hiển thị tầng đầu tiên
    const firstFloor = branch.floors[0];

    campusMap.src = firstFloor.file;

    campusMap.alt =
        `Bản đồ ${branch.name} - ${firstFloor.name}`;

    selectedFloor.textContent = firstFloor.name;


    // Nếu chỉ có một bản đồ thì disable dropdown tầng
    if (branch.floors.length <= 1) {

        floorSelect.classList.add("disabled");

    } else {

        floorSelect.classList.remove("disabled");

    }

}


// ================================
// CLICK CHỌN CƠ SỞ
// ================================

branchOptions.forEach(option => {

    option.addEventListener("click", function () {

        const branchId = this.dataset.branch;

        const branch = mapData[branchId];

        if (!branch) {
            return;
        }


        currentBranch = branchId;


        // Đổi tên cơ sở
        selectedBranch.textContent = branch.name;


        // Active cơ sở
        branchOptions.forEach(item => {
            item.classList.remove("active");
        });

        this.classList.add("active");


        // Load tầng của cơ sở
        loadFloors(branchId);


        // Đóng dropdown
        branchSelect.classList.remove("open");

    });

});


// ================================
// MỞ / ĐÓNG DROPDOWN CƠ SỞ
// ================================

branchButton.addEventListener("click", function (event) {

    event.stopPropagation();

    branchSelect.classList.toggle("open");

    // Đóng dropdown tầng
    floorSelect.classList.remove("open");

});


// ================================
// MỞ / ĐÓNG DROPDOWN TẦNG
// ================================

floorButton.addEventListener("click", function (event) {

    event.stopPropagation();

    // Nếu chỉ có 1 bản đồ thì không mở
    if (mapData[currentBranch].floors.length <= 1) {
        return;
    }

    floorSelect.classList.toggle("open");

    // Đóng dropdown cơ sở
    branchSelect.classList.remove("open");

});


// ================================
// CLICK RA NGOÀI
// ================================

document.addEventListener("click", function (event) {

    if (!branchSelect.contains(event.target)) {
        branchSelect.classList.remove("open");
    }

    if (!floorSelect.contains(event.target)) {
        floorSelect.classList.remove("open");
    }

});


// ================================
// KHỞI TẠO
// ================================

loadFloors("coso1");