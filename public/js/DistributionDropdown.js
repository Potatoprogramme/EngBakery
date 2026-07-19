let storesCache = null;

function loadStores(selectedCategoryId = null) {
  return $.ajax({
    url: baseUrl + "DistributionCategory/FetchAll",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response && response.success) {
        storesCache = response.data; // cache the data
        populateStoreDropdown(storesCache, selectedCategoryId);
        if (typeof window.syncDistributionCategoryUi === "function") {
          window.syncDistributionCategoryUi();
        }
      }
    },
    error: function (xhr) {
      console.error(
        "Error fetching stores:",
        xhr.responseJSON?.message ||
          xhr.responseJSON?.error ||
          "Failed to fetch stores.",
      );
    },
  });
}

function populateStoreDropdown(data, selectedCategoryId = null) {
  const $select = $("#distributionGroupName");
  const currentVal = selectedCategoryId != null && selectedCategoryId !== ""
    ? String(selectedCategoryId)
    : $select.val();

  $select.find("option:not(:first)").remove();

  $.each(data, function (_, store) {
    $select.append(
      $("<option>", {
        value: store.dist_cat_id,
        text: store.name,
      }),
    );
  });

  if (currentVal) {
    $select.val(currentVal);
    $select.find(`option[value="${currentVal}"]`).prop("selected", true);
  } else {
    $select.val("");
  }
}
