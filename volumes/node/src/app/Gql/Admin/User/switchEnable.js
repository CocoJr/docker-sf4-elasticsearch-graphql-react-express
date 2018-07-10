/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";
import {ErrorFragment} from '../../Fragment/error';

export default gql`
  mutation adminUserSwitchEnable($id: ID!, $enable: Boolean!) {
    adminUserSwitchEnable(input: {id: $id, enable: $enable}) {
      user {
        id,
        isActive
      }
      errors {
        ...ErrorFragment
      }
    }
  }
  ${ErrorFragment}
`;
