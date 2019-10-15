  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }
    else
    {
      $this->setPage(1);
    }

  // max per page
    if ($request->getParameter('max_per_page'))
    {
      $this->setMaxPerPage($request->getParameter('max_per_page'));
    }

    $this->sidebar_status = $this->configuration->getListSidebarStatus();
    $this->pager = $this->getPager();

    //Start - thongnq1 - 03/05/2013 - fix loi xoa du lieu tren trang danh sach
    if ($request->getParameter('current_page'))
    {
      $current_page = $request->getParameter('current_page');
      $this->setPage($current_page);
      $this->pager = $this->getPager();
    }
    //end thongnq1

    $this->sort = $this->getSort();
  }
