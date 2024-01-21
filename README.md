User
 - ID
 - EMail
 - Name
 - Password

SSO
 - ID
 - UserID
 - Token
 - ...

Budget
 - ID
 - UserID (Owner)
 - Name

Account
 - ID
 - BudgetID
 - UserID (Owner)
 - Name
 - Balance
 - UnclearedBalance

Bucket
 - ID
 - Month
 - Year
 - BudgetID
 - UserID (Owner)
 - AvailableBalance

Transaction
 - ID
 - AccountID
 - UserID (Owner)
 - CategoryID
 - PayeeID
 - BudgetID
 - Memo
 - Cleared
 - Credit
 - Debit
 - RunningBalance

CategoryGroup
 - ID
 - BudgetID
 - UserID (Owner)
 - Name

Category
 - ID
 - CategoryGroupID
 - BudgetID
 - UserID (Owner)
 - Name

Payee
 - ID
 - BudgetID
 - UserID (Owner)
 - Name
 - LinkCategoryID

BucketCategory
 - ID
 - BudgetID
 - UserID (Owner)
 - CategoryGroupID
 - Allocated
 - Used


Budget

    -< Accounts
        -< Transactions
            -| Category
            -| Payee
    -< Buckets
        -< CategoryGroups
            -< Categories
        -< Transactions
        -< Payees
