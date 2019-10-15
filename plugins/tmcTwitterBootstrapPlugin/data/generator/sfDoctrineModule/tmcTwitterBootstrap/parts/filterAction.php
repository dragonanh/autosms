  public function executeFilter(sfWebRequest $request)
  {
    $this->setPage(1);

    if ($request->hasParameter('_reset'))
    {
      $this->setFilters($this->configuration->getFilterDefaults());

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }

    $this->filters = $this->configuration->getFilterForm($this->getFilters());
        //Chuyennv2 trim du lieu
      $filterValues = $request->getParameter($this->filters->getName());
      foreach ($filterValues as $key => $value)
      {
          if (isset($filterValues[$key]['text']))
          {
          $filterValues[$key]['text'] = trim($filterValues[$key]['text']);
          }
      }

  $this->filters->bind($filterValues);
    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }
    $this->sidebar_status = $this->configuration->getListSidebarStatus();
    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate('index');
  }
