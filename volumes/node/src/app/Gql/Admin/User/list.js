import gql from "graphql-tag";
import {UserAdminBaseFragment} from "./../../Fragment/user";

export default gql`
query adminUsers($page: Int, $limit: Int, $orderBy: String, $orderDir: String, $searches: array) {
    adminUsers(page: $page, limit: $limit, orderBy: $orderBy, orderDir: $orderDir, searches: $searches) {
        page,
        limit,
        total,
        items {
            ...UserAdminBaseFragment
        }
    }
}
${UserAdminBaseFragment}
`;
