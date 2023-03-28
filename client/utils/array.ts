type Defined<T> = T extends null | undefined ? never : T
export const truthy = <T>(value: T): value is Defined<T> => value !== undefined && value !== null
