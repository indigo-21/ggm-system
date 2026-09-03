<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when an order/quote cannot be persisted for a domain reason
 * (e.g. a duplicate "Others" masterfile record or a child-row save failure).
 *
 * Raised inside the upsert transaction so that the whole operation rolls back,
 * and caught by OrderService::order_upsert to surface a friendly message.
 */
class OrderPersistenceException extends RuntimeException {}
