function headerInit() {
  urlMenuCheck();
}

function mobileMenuRemote(remote) {
  if(remote == undefined) {
    $("#mobile_menu_wrap").fadeOut("fast");
    $("#mobile_menu_container").removeClass("on");
  } else {
    $("#mobile_menu_wrap").fadeIn("fast");
    $("#mobile_menu_container").addClass("on");
    // 현재 URL에 해당하는 섹션 자동 열기
    var section = window.location.pathname.split('/')[1];
    if (section) {
      var target = document.querySelector('#mobile_menu_container .menu_wrap[data-section="' + section + '"]');
      if (target && !target.classList.contains('open')) {
        target.classList.add('open');
      }
    }
  }
}

function toggleMobileSection(btn) {
  var wrap = btn.closest('.menu_wrap');
  wrap.classList.toggle('open');
}


function loadingOn() {
  const loading = document.querySelector('#loading_wrap');
  loading.classList.add('on');
}

function loadingOff() {
  const loading = document.querySelector('#loading_wrap');
  loading.classList.remove('on');
}

function urlMenuCheck() {
  const menuName = window.location.pathname.split('/');

  if(menuName.length > 2) {
    const ele = document.querySelector('#header .pc_header .left_box .menu_wrap .menu_box[name='+menuName[1]+']');
    if(ele) {
      ele.classList.add("on");
    }
  }
}

const getParam = (name) => {
  let params = location.search.substr(location.search.indexOf("?") + 1);
  let value = "";
  params = params.split("&");

  for (let i = 0; i < params.length; i++) {
    temp = params[i].split("=");
    if ([temp[0]] == name) { value = temp[1]; }
  }

  return value;
}

/**
 * 파라미터 업데이트 함수
 * @param {String} paramName 파라미터네임
 * @param {String} updateParamValue 파라미터밸류
 */
const updateParam = (paramName, updateParamValue) => {
  // 현재 URL 가져오기
  const urlObj = new URL(window.location.href);
  
  // page 파라미터 업데이트
  urlObj.searchParams.set(paramName, updateParamValue);
  
  // URL 업데이트 (history API 사용)
  window.history.replaceState(null, '', urlObj.toString());
  
  console.log("Updated URL:", urlObj.toString());
}

const getPageBlock = (currentPage, totalPage, blockSize) => {
  if (currentPage > totalPage) {
    currentPage = totalPage;
  }

  const currentBlock = Math.ceil(currentPage / blockSize);
  const startPage = (currentBlock - 1) * blockSize + 1;
  const endPage = Math.min(startPage + blockSize - 1, totalPage);

  let pages = [];
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return pages;
}
  
const getPageBlockHtml = (currentPage, totalPage, pages) => {
  let prevPage = pages[0] - 1;
  if (prevPage <= 0) {
    prevPage = 1;
  }

  let renderHtml = `
    <button class="prev_btn" title="이전" onclick="movePage(${prevPage})">
      <i class="fa-solid fa-angle-left"></i>
    </button>
    <div class="paging_box">
  `;

  renderHtml += pages.reduce((acc, page) => {
    acc += `
      <button class="page_btn" onclick="movePage(${page})">${page}</button>
    `;

    return acc;
  }, "");

  let nextPage = pages[pages.length - 1] + 1;
  if (nextPage > totalPage) {
    nextPage = totalPage;
  }
  renderHtml += `
    </div>
    <button class="next_btn" title="다음" onclick="movePage(${nextPage})">
      <i class="fa-solid fa-angle-right"></i>
    </button>
  `;

  return renderHtml;
}