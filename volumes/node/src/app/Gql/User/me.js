import gql from "graphql-tag";
import {UserBaseFragment} from "./../Fragment/user";

export default gql`
query {
    userMe {
        ...UserBaseFragment
    }
}
${UserBaseFragment}
`;
