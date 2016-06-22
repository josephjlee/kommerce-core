# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
The [Keep a Changelog](http://keepachangelog.com/) format will be
used to track changes in this project.

## [Unreleased]
### Added
- Cart
  - AddCartItemCommand
  - AddCouponToCartCommand
  - CopyCartItemsCommand
  - CreateCartCommand
  - DeleteCartItemCommand
  - RemoveCartCommand
  - RemoveCouponFromCartCommand
  - SetCartFlatRateShipmentRateCommand
  - SetCartSessionIdCommand
  - SetCartTaxRateByZip5AndStateCommand
  - SetCartUserCommand
  - UpdateCartItemQuantityCommand
  - GetCartQuery
  - GetCartBySessionIdQuery
  - GetCartByUserIdQuery
- Option
  - GetOptionQuery
- Order
  - GetOrderItemQuery
  - GetOrdersByUserQuery
- Product
  - AddTagToProductCommand
  - CreateProductCommand
  - RemoveImageFromProductCommand
  - RemoveTagFromProductCommand
  - UpdateProductCommand
  - GetProductQuery
  - GetProductsByIdsQuery
  - GetRelatedProductsQuery
  - GetRandomProductsQuery
- Shipment
  - GetLowestShipmentRatesByDeliveryMethod
- Tag
  - GetTagsByIdsQuery
- User
  - CreateUserCommand
  - ImportUsersFromCSVCommand
  - LoginCommand
  - GetUserQuery
  - GetUserByEmailQuery
### Changed
- Migrate to UUIDs for primary keys
- LoginWithTokenQuery to LoginWithTokenCommand
- Moved raising OrderShippedEvent from OrderService::addShipment() to Order::addShipment()
- Moved raising ResetPasswordEvent from UserService::requestPasswordResetToken() to UserToken::createResetPasswordToken()
- Changed CartService::removeCoupon() to use $couponId instead of $couponIndex