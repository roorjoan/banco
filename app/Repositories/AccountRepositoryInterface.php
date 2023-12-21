<?php

namespace App\Repositories;

interface AccountRepositoryInterface
{
    public function storeAccount($request);
    public function depositTransaction($request);
    public function withdrawalTransaction($request);
    public function balanceAccount($id);
    public function transactionsAccount($id);
}
